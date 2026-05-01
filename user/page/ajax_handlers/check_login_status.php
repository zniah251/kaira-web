<?php
session_start();
header('Content-Type: application/json');

$response = ['loggedIn' => false];

// GIẢ ĐỊNH: Bạn lưu UID của người dùng vào session với key là 'uid'
// Nếu bạn đang dùng 'user_id', thì không cần thay đổi.
if (isset($_SESSION['uid']) && $_SESSION['uid'] > 0) {
    $response['loggedIn'] = true;
}

echo json_encode($response);
?>