<?php
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; // Điều chỉnh đường dẫn

header('Content-Type: application/json');

if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
    $pid = intval($_GET['pid']);

    // Truy vấn để lấy tất cả thông tin sản phẩm và category name
    $sql = "SELECT p.*, c.cname as category_name, c.cid as category_cid 
            FROM product p 
            JOIN category c ON p.cid = c.cid 
            WHERE p.pid = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        // Không cần truy vấn categories ở đây nữa
        echo json_encode(['success' => true, 'product' => $product]); // Chỉ trả về product
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
}

$conn->close();
?>