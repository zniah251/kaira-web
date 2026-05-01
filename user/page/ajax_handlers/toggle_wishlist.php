<?php
session_start();
header('Content-Type: application/json');

// Kiểm tra xem người dùng đã đăng nhập chưa
// GIẢ ĐỊNH: Bạn lưu UID của người dùng vào session với key là 'uid'
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.']);
    exit();
}

// Lấy UID từ session
$user_id = $_SESSION['uid']; // Thay đổi từ $_SESSION['user_id'] sang $_SESSION['uid']
$input = json_decode(file_get_contents('php://input'), true);
$product_id = isset($input['productId']) ? (int)$input['productId'] : 0;

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ.']);
    exit();
}

// Kết nối cơ sở dữ liệu
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kiểm tra xem sản phẩm đã có trong wishlist của người dùng chưa
    // Sử dụng cột 'uid' và 'pid' của bảng `wishlist` của bạn
    $stmt = $conn->prepare("SELECT COUNT(*) FROM wishlist WHERE uid = :uid AND pid = :pid");
    $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':pid', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $isInWishlist = $stmt->fetchColumn();

    if ($isInWishlist) {
        // Nếu đã có, xóa khỏi wishlist
        // Sử dụng cột 'uid' và 'pid' của bảng `wishlist` của bạn
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE uid = :uid AND pid = :pid");
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':pid', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Sản phẩm đã được xóa khỏi danh sách yêu thích.']);
    } else {
        // Nếu chưa có, thêm vào wishlist
        // Sử dụng cột 'uid', 'pid', và 'create_at' của bảng `wishlist` của bạn
        $stmt = $conn->prepare("INSERT INTO wishlist (uid, pid, create_at) VALUES (:uid, :pid, NOW())");
        $stmt->bindParam(':uid', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':pid', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Sản phẩm đã được thêm vào danh sách yêu thích.']);
    }

} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
} finally {
    $conn = null;
}
?>