<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để thực hiện thao tác này.'
    ]);
    exit;
}

// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

// Get JSON data from request
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$order_id = intval($data['order_id'] ?? 0);
$uid = $_SESSION['uid'] ?? 0;

$response = ['success' => false];

if ($order_id > 0 && $uid > 0) {
    try {
        // First check if the order belongs to the user and is in Pending status
        $checkQuery = "SELECT destatus, paystatus, paymethod, uid, totalfinal FROM orders WHERE oid = ? AND uid = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ii", $order_id, $uid);
        $stmt->execute();
        $stmt->bind_result($destatus, $paystatus, $paymethod, $uid_from_db, $totalfinal_from_db);
        $stmt->fetch();
        $stmt->close();

        if ($destatus === 'Pending') {
            // Hoàn tiền nếu đơn hàng đang Pending, đã thanh toán và là thanh toán bằng ví điện tử
            if ($paystatus === 'Paid' && $paymethod === 'E-wallet') {
                $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE uid = ?");
                $stmt->bind_param("di", $totalfinal_from_db, $uid_from_db);
                $stmt->execute();
                $stmt->close();

                // Update the order's paystatus to 'Refunded'
                $stmt = $conn->prepare("UPDATE orders SET paystatus = 'Refunded' WHERE oid = ?");
                $stmt->bind_param("i", $order_id);
                $stmt->execute();
                $stmt->close();
            }

            // Cộng lại stock cho các sản phẩm trong đơn hàng này
            $stmt = $conn->prepare("SELECT pid, quantity FROM order_detail WHERE oid = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $pid = $row['pid'];
                $quantity = $row['quantity'];
                $stmtUpdate = $conn->prepare("UPDATE product SET stock = stock + ? WHERE pid = ?");
                $stmtUpdate->bind_param("ii", $quantity, $pid);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
            $stmt->close();

            // Nếu có voucher, cập nhật lại user_voucher.status = 'unused'
            $stmt = $conn->prepare("SELECT vid FROM orders WHERE oid = ?");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->bind_result($vid);
            $stmt->fetch();
            $stmt->close();
            if ($vid) {
                $stmt = $conn->prepare("UPDATE user_voucher SET status = 'unused' WHERE uid = ? AND vid = ?");
                $stmt->bind_param("ii", $uid, $vid);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Hủy đơn hàng (cập nhật destatus)
        $stmt = $conn->prepare("UPDATE orders SET destatus = 'Cancelled' WHERE oid = ? AND uid = ?");
        $stmt->bind_param("ii", $order_id, $uid);
        $success = $stmt->execute();
        $stmt->close();

        $response['success'] = $success;
        $response['message'] = $success ? 'Đơn hàng đã được hủy thành công.' : 'Có lỗi xảy ra khi hủy đơn hàng.';
    } catch (Exception $e) {
        $response['message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
    }
}

echo json_encode($response);

$conn->close();