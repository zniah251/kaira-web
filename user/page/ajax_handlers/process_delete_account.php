<?php
session_start();
header('Content-Type: application/json');

// Kiểm tra AJAX request
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Truy cập không hợp lệ.']);
    exit();
}

// 1. Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit();
}

// 2. Bao gồm file kết nối CSDL
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; // Điều chỉnh đường dẫn nếu cần

$uid = $_SESSION['uid'];
$response = ['success' => false, 'message' => 'Đã xảy ra lỗi không xác định.'];

try {
    // 3. Lấy dữ liệu từ AJAX request (JSON)
    $input_data = file_get_contents('php://input');
    $data = json_decode($input_data, true);

    $password_input = $data['password'] ?? '';

    if (empty($password_input)) {
        throw new Exception("Vui lòng nhập mật khẩu của bạn để xác nhận.");
    }

    // 4. Kiểm tra xem người dùng có đơn hàng nào đang ở trạng thái 'Confirmed' hoặc 'Shipping' không
    $stmt = $conn->prepare("SELECT COUNT(*) as active_orders FROM orders WHERE uid = ? AND destatus IN ('Confirmed', 'Shipping')");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $order_count = $result->fetch_assoc()['active_orders'];
    $stmt->close();

    if ($order_count > 0) {
        throw new Exception("Không thể xóa tài khoản vì bạn có đơn hàng đang trong trạng thái chờ lấy hàng hoặc đang vận chuyển. Vui lòng chờ đơn hàng được giao hoặc hủy đơn hàng trước khi xóa tài khoản.");
    }

    // 5. Lấy mật khẩu đã hash của người dùng từ CSDL
    $stmt = $conn->prepare("SELECT password FROM users WHERE uid = ? LIMIT 1");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close(); // Đóng statement sau khi fetch

    if (!$user) {
        throw new Exception("Tài khoản không tồn tại."); // Người dùng có thể đã bị xóa
    }

    $hashed_password_from_db = $user['password'];

    // 6. Xác minh mật khẩu
    if (!password_verify($password_input, $hashed_password_from_db)) {
        throw new Exception("Mật khẩu không chính xác.");
    }

    // 7. Cộng lại stock cho các đơn hàng Pending trước khi xóa tài khoản
    $stmt = $conn->prepare("SELECT oid FROM orders WHERE uid = ? AND destatus = 'Pending'");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $pendingOids = [];
    while ($row = $result->fetch_assoc()) {
        $pendingOids[] = $row['oid'];
    }
    $stmt->close();

    foreach ($pendingOids as $oid) {
        $stmt = $conn->prepare("SELECT pid, quantity FROM order_detail WHERE oid = ?");
        $stmt->bind_param("i", $oid);
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
    }

    // 8. Xóa tài khoản (hoặc đánh dấu is_deleted)
    // Cảnh báo: DELETE FROM là hành động không thể hoàn tác.
    // Đối với ứng dụng thực tế, thường nên đánh dấu tài khoản là 'is_deleted'
    // thay vì xóa vĩnh viễn để giữ lại tính toàn vẹn dữ liệu cho các đơn hàng cũ, v.v.
    
    // Ví dụ: Đánh dấu là đã xóa (Khuyến nghị)
    // Bạn cần thêm cột `is_deleted` (TINYINT default 0) vào bảng `users`
    // $stmt = $conn->prepare("UPDATE users SET is_deleted = 1, updated_at = NOW() WHERE uid = ?");
    // $stmt->bind_param("i", $uid);

    // Ví dụ: Xóa hoàn toàn tài khoản (Cẩn trọng khi sử dụng!)
    $stmt = $conn->prepare("DELETE FROM users WHERE uid = ?");
    $stmt->bind_param("i", $uid);
    
    if ($stmt->execute()) {
        // Hủy session của người dùng sau khi xóa tài khoản
        session_destroy();
        $response = ['success' => true, 'message' => 'Tài khoản của bạn đã được xóa thành công.'];
    } else {
        throw new Exception("Không thể xóa tài khoản. Vui lòng thử lại.");
    }
    $stmt->close();

} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
    error_log("Lỗi xóa tài khoản cho UID $uid: " . $e->getMessage());
} finally {
    if (isset($conn)) {
        $conn->close(); // Đóng kết nối mysqli
    }
    echo json_encode($response);
}
?>