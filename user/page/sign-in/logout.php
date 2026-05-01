<?php
session_start(); // Bắt đầu session
// Hủy tất cả các biến session
$_SESSION = array();

// Nếu muốn xóa cookie session, cần xóa cả session cookie.
// Lưu ý: Thao tác này sẽ làm mất session cho toàn bộ tên miền.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
// Cuối cùng, hủy session
session_destroy();
// Chuyển hướng người dùng về trang chủ
header("Location: /e-web/user/"); // Đảm bảo đường dẫn này đúng với trang chủ của bạn
exit();
?>