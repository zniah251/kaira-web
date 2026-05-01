<?php
session_start();
header('Content-Type: application/json');

$response = ['inWishlist' => false];

// GIẢ ĐỊNH: Bạn lưu UID của người dùng vào session với key là 'uid'
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    echo json_encode($response);
    exit();
}

// Lấy UID từ session
$user_id = $_SESSION['uid'];
$input = json_decode(file_get_contents('php://input'), true);
$product_id = isset($input['productId']) ? (int)$input['productId'] : 0;

if ($product_id <= 0) {
    echo json_encode($response);
    exit();
}

// Kết nối cơ sở dữ liệu
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";


// Sử dụng cột 'uid' và 'pid' của bảng `wishlist` của bạn
$stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE uid = ? AND pid = ?");
if ($stmt) {
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->bind_result($isInWishlist);
    $stmt->fetch();
    if ($isInWishlist) {
        $response['inWishlist'] = true;
    }
    $stmt->close();
} else {
    error_log('Database prepare error in check_product_in_wishlist.php: ' . $conn->error);
}

$conn->close();

echo json_encode($response);
?>