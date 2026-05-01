<?php
session_start(); // Bắt đầu session để truy cập UID người dùng
header('Content-Type: application/json'); // Đặt header để trả về JSON

// Kiểm tra xem đây có phải là AJAX request không (tùy chọn nhưng tốt)
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Truy cập không hợp lệ.']);
    exit();
}

// 1. Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập.']);
    exit();
}

// 2. Bao gồm file kết nối CSDL
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; // Điều chỉnh đường dẫn nếu cần

// Lấy UID của người dùng từ session
$uid = $_SESSION['uid'];

// 3. Lấy dữ liệu từ AJAX request (JSON)
$input_data = file_get_contents('php://input');
$data = json_decode($input_data, true);

$old_password_input = $data['old_password'] ?? '';
$new_password_input = $data['new_password'] ?? '';

$response = ['success' => false, 'message' => 'Đã xảy ra lỗi không xác định.'];

try {
    // 4. Validation phía Server
    if (empty($old_password_input) || empty($new_password_input)) {
        throw new Exception("Vui lòng điền đầy đủ mật khẩu cũ và mật khẩu mới.");
    }

    // (Optional) Thêm validation độ mạnh mật khẩu ở đây
    if (strlen($new_password_input) < 8) {
        throw new Exception("Mật khẩu mới phải có ít nhất 8 ký tự.");
    }
    // Bạn có thể thêm các regex để kiểm tra ký tự đặc biệt, số, chữ hoa/thường...

    // 5. Lấy mật khẩu đã hash của người dùng từ CSDL (dùng MySQLi)
    $stmt = $conn->prepare("SELECT password FROM users WHERE uid = ? LIMIT 1");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        // Không tìm thấy người dùng (hiếm khi xảy ra nếu đã kiểm tra session)
        throw new Exception("Người dùng không tồn tại.");
    }

    $hashed_password_from_db = $user['password'];

    // 6. Xác minh mật khẩu cũ
    if (!password_verify($old_password_input, $hashed_password_from_db)) {
        throw new Exception("Mật khẩu cũ không chính xác.");
    }

    // 7. Hash mật khẩu mới
    $new_hashed_password = password_hash($new_password_input, PASSWORD_DEFAULT);
    if ($new_hashed_password === false) {
        throw new Exception("Không thể tạo hash mật khẩu. Vui lòng thử lại.");
    }

    // 8. Cập nhật mật khẩu mới vào CSDL
    $stmt = $conn->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE uid = ?");
    $stmt->bind_param("si", $new_hashed_password, $uid);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = ['success' => true, 'message' => 'Mật khẩu đã được thay đổi thành công.'];
    } else {
        // Trường hợp không có hàng nào bị ảnh hưởng (mật khẩu mới giống mật khẩu cũ, hoặc lỗi không xác định)
        throw new Exception("Không có gì thay đổi hoặc đã xảy ra lỗi.");
    }

    $stmt->close();
} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
    error_log("Lỗi thay đổi mật khẩu cho UID $uid: " . $e->getMessage());
} finally {
    // Đóng kết nối CSDL (nếu bạn không dùng persistent connection)
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    echo json_encode($response);
}
?>