<?php
require_once "../../../connect.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: /e-web/user/page/sign-in/login2.php");
    exit();
}

$uid = $_SESSION['uid'];
$products = $_SESSION['cart'] ?? [];

$fullname = $_POST['fullname'] ?? '';
$address  = $_POST['address'] ?? '';
$phone    = $_POST['phone'] ?? '';
$method   = $_POST['payment_method'] ?? 'MOMO';
$voucher  = trim($_POST['voucher'] ?? '');

$vid = 0;
$discount = 0;
$shipping = 30000;
$voucher_minprice = 0;

// 1. Tính tổng tiền hàng trước
$total = 0;
foreach ($products as $p) {
    $total += $p['price'] * $p['quantity'];
}

// 2. Xử lý voucher nếu có và đủ điều kiện
if ($voucher !== '') {
    $stmt = $conn->prepare("SELECT vid, discount, minprice, name, expiry FROM voucher WHERE name = ?");
    $stmt->bind_param("s", $voucher);
    $stmt->execute();
    $stmt->bind_result($vidFound, $discountPercent, $minprice, $vname, $expiry);
    if ($stmt->fetch()) {
        // Kiểm tra hạn sử dụng
        if (strtotime($expiry) >= strtotime(date('Y-m-d'))) {
            if ($total >= $minprice) { // chỉ áp dụng nếu đủ điều kiện
                $vid = $vidFound;
                $voucher_minprice = $minprice;
                if (strtolower(trim($vname)) === 'free shipping') {
                    $shipping = 0;
                } else {
                    $discount = $total * ($discountPercent / 100);
                }
            }
            // Nếu không đủ điều kiện, không set $vid, $discount
        }
        // Nếu hết hạn, không set $vid, $discount
    }
    $stmt->close();
}

// 3. Tính tổng cuối cùng
$totalfinal = $total + $shipping - $discount;

// 4. Kiểm tra số dư ví điện tử nếu thanh toán bằng WALLET
if ($method === 'WALLET') {
    // Lấy số dư hiện tại của user
    $stmt = $conn->prepare("SELECT balance FROM users WHERE uid = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
    
    $current_balance = $user_data['balance'] ?? 1000000; // Default balance for new users
    
    if ($current_balance < $totalfinal) {
        // Số dư không đủ
        header("Location: checkout.php?error=insufficient_balance&required=" . $totalfinal . "&current=" . $current_balance);
        exit();
    }
}

// 5. Bắt đầu transaction
$conn->begin_transaction();

try {
    // 6. Lưu đơn hàng
    $paystatus = ($method === 'WALLET') ? 'Paid' : 'Pending';
    $paymethod_to_save = ($method === 'WALLET') ? 'E-wallet' : $method;
    $stmt = $conn->prepare("INSERT INTO orders (uid, totalfinal, price, destatus, paymethod, paystatus, create_at, vid) VALUES (?, ?, ?, 'Pending', ?, ?, NOW(), ?)");
    $vid_to_save = $vid ?? 0;
    $stmt->bind_param("iddssi", $uid, $totalfinal, $total, $paymethod_to_save, $paystatus, $vid_to_save);
    $stmt->execute();

    $oid = $stmt->insert_id;

    // 7. Lưu chi tiết đơn hàng
    $stmt = $conn->prepare("INSERT INTO order_detail (oid, pid, quantity, size, color, price) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($products as $product) {
        $stmt->bind_param("iiissd",
            $oid,
            $product['pid'],
            $product['quantity'],
            $product['size'],
            $product['color'],
            $product['price']
        );
        $stmt->execute();
    }

    // 8. Cập nhật tồn kho
    $stmt = $conn->prepare("UPDATE product SET stock = stock - ?, sold = sold + ? WHERE pid = ?");
    foreach ($products as $product) {
        $stmt->bind_param("iii",
            $product['quantity'],
            $product['quantity'],
            $product['pid']
        );
        $stmt->execute();
    }

    // 9. Nếu có voucher, cập nhật user_voucher.status = 'used'
    if ($vid_to_save) {
        $stmt = $conn->prepare("UPDATE user_voucher SET status = 'used' WHERE uid = ? AND vid = ?");
        $stmt->bind_param("ii", $uid, $vid_to_save);
        $stmt->execute();
    }

    // 10. Nếu thanh toán bằng ví điện tử, trừ tiền từ balance
    if ($method === 'WALLET') {
        $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE uid = ?");
        $stmt->bind_param("di", $totalfinal, $uid);
        $stmt->execute();
        $stmt->close();
    }

    $conn->commit();
    unset($_SESSION['cart']);

    // 11. Redirect
    switch ($method) {
        case 'MOMO':
            header("Location: momo_payment_info.php?orderId=$oid");
            break;
        case 'BANK':
            header("Location: bank_payment_info.php?orderId=$oid");
            break;
        case 'WALLET':
            header("Location: wallet_payment_success.php?orderId=$oid&fullname=$fullname&address=$address&phone=$phone");
            break;
        case 'COD':
        default:
            header("Location: confirm_shipping.php?orderId=$oid&fullname=$fullname&address=$address&phone=$phone");
    }
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
    exit();
}
?>