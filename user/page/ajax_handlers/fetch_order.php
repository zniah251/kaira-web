<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các domain
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập.']);
    http_response_code(401);
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$uid = $_SESSION['uid'];
$status = $_GET['status'] ?? 'all';
$fromDate = $_GET['fromDate'] ?? null;
$toDate = $_GET['toDate'] ?? null;
$response_orders = [];

try {
    // 1. Lấy thông tin chung của các đơn hàng
    $sql_orders = "SELECT oid, totalfinal, price, vid, destatus, paymethod, paystatus, create_at 
                  FROM `orders` WHERE uid = ?";
    $params = [$uid];
    $types = "i";

    if ($status !== 'all' && $status !== 'All') {
        if ($status === 'PendingPaid') {
            $sql_orders .= " AND destatus = 'Pending' AND paystatus = 'Paid'";
        } else if ($status === 'Confirmed') {
            $sql_orders .= " AND destatus = 'Confirmed' AND paystatus = 'Paid'";
        } else {
            $sql_orders .= " AND destatus = ?";
            $params[] = $status;
            $types .= "s";
        }
    }

    if ($fromDate && $toDate) {
        $sql_orders .= " AND DATE(create_at) BETWEEN ? AND ?";
        $params[] = $fromDate;
        $params[] = $toDate;
        $types .= "ss";
    }

    $sql_orders .= " ORDER BY create_at DESC";

    $stmt_orders = $conn->prepare($sql_orders);
    if ($stmt_orders === false) {
        throw new Exception("Lỗi prepare SQL orders: " . $conn->error);
    }
    $stmt_orders->bind_param($types, ...$params);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();

    $order_ids = [];
    $raw_orders = [];
    while ($order_row = $result_orders->fetch_assoc()) {
        $raw_orders[$order_row['oid']] = $order_row;
        $raw_orders[$order_row['oid']]['products'] = [];
        $order_ids[] = $order_row['oid'];
    }
    $stmt_orders->close();

    // 2. Lấy chi tiết sản phẩm cho tất cả các đơn hàng đã tìm thấy
    if (!empty($order_ids)) {
        $placeholders = implode(',', array_fill(0, count($order_ids), '?'));
        $sql_details = "
            SELECT od.oid, od.quantity, od.size, od.color, od.price AS item_price_at_order,
                   p.title, p.thumbnail, p.price AS product_original_price, p.discount
            FROM order_detail od
            JOIN product p ON od.pid = p.pid
            WHERE od.oid IN ($placeholders)
            ORDER BY od.oid, p.title";

        $stmt_details = $conn->prepare($sql_details);
        if ($stmt_details === false) {
            throw new Exception("Lỗi prepare SQL order_detail: " . $conn->error);
        }
        $types_details = str_repeat('i', count($order_ids));
        $stmt_details->bind_param($types_details, ...$order_ids);
        $stmt_details->execute();
        $result_details = $stmt_details->get_result();

        while ($detail_row = $result_details->fetch_assoc()) {
            // Xử lý đường dẫn thumbnail
            if (strpos($detail_row['thumbnail'], 'admin/assets/images/') === 0) {
                $detail_row['thumbnail'] = substr($detail_row['thumbnail'], strlen('admin/assets/images/'));
            }
            $raw_orders[$detail_row['oid']]['products'][] = $detail_row;
        }
        $stmt_details->close();
    }
    
    // 3. Xử lý logic tính toán và định dạng dữ liệu
    foreach ($raw_orders as $oid => $order) {
        $order_data = [
            'oid' => $order['oid'],
            'create_at' => $order['create_at'],
            'destatus' => $order['destatus'],
            'paymethod' => $order['paymethod'],
            'paystatus' => $order['paystatus'],
            'totalfinal' => (float)$order['totalfinal'],
            'original_order_subtotal' => (float)$order['price'],
            'voucher_discount' => 0,
            'shipping_fee' => 30000,
            'shipping_discount' => 16500,
            'products' => []
        ];

        // Lấy thông tin voucher nếu có
        if (!empty($order['vid'])) {
            $stmt_voucher = $conn->prepare("SELECT minprice FROM voucher WHERE vid = ?");
            if ($stmt_voucher) {
                $stmt_voucher->bind_param("i", $order['vid']);
                $stmt_voucher->execute();
                $result_voucher = $stmt_voucher->get_result();
                if ($voucher = $result_voucher->fetch_assoc()) {
                    $order_data['voucher_discount'] = (float)$voucher['minprice'];
                }
                $stmt_voucher->close();
            }
        }
        
        foreach ($order['products'] as $product) {
            $order_data['products'][] = [
                'title' => $product['title'],
                'thumbnail' => $product['thumbnail'],
                'quantity' => (int)$product['quantity'],
                'size' => $product['size'],
                'color' => $product['color'],
                'item_price_at_order' => (float)$product['item_price_at_order'],
                'product_original_price' => (float)$product['product_original_price'],
                'discount_percentage' => (float)$product['discount']
            ];
        }
        $response_orders[] = $order_data;
    }

    $conn->close();
    echo json_encode(['success' => true, 'orders' => $response_orders]);

} catch (Exception $e) {
    error_log("Lỗi lấy đơn hàng: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi khi lấy đơn hàng: ' . $e->getMessage()]);
    http_response_code(500);
}
?>