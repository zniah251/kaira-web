<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy tất cả dữ liệu từ POST request
    $pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $thumbnail = isset($_POST['thumbnail']) ? trim($_POST['thumbnail']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
    $size = isset($_POST['size']) ? trim($_POST['size']) : '';
    $size2 = isset($_POST['size2']) ? trim($_POST['size2']) : '';
    $size3 = isset($_POST['size3']) ? trim($_POST['size3']) : '';
    $color = isset($_POST['color']) ? trim($_POST['color']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $cid = isset($_POST['category_cid']) ? intval($_POST['category_cid']) : 0;

    // LẤY CÁC BIẾN MỚI TỪ POST
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0.0;
    $thumbnail2 = isset($_POST['thumbnail2']) ? trim($_POST['thumbnail2']) : '';
    $thumbnail3 = isset($_POST['thumbnail3']) ? trim($_POST['thumbnail3']) : '';
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 0.0; // Giả định rating là số thực (ví dụ: 4.5)
    $sold = isset($_POST['sold']) ? intval($_POST['sold']) : 0;
    $color2 = isset($_POST['color2']) ? trim($_POST['color2']) : '';


    // Kiểm tra tính hợp lệ cơ bản của dữ liệu
    if ($pid <= 0 || empty($title) || $price < 0 || $stock < 0 || $cid <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data. Please check Product ID, Title, Price, Stock, or Category.']);
        $conn->close();
        exit();
    }

    // CÂU LỆNH SQL UPDATE MỚI (BAO GỒM TẤT CẢ CÁC CỘT BẠN MUỐN CẬP NHẬT)
    // Đảm bảo các cột này tồn tại trong bảng 'product' của bạn
    $sql = "UPDATE product SET 
                title = ?, 
                thumbnail = ?, 
                price = ?, 
                stock = ?, 
                size = ?, 
                size2 = ?, 
                size3 = ?, 
                color = ?, 
                description = ?, 
                cid = ?,
                discount = ?,        
                thumbnail2 = ?,      -- Thêm cột thumbnail2
                thumbnail3 = ?,      -- Thêm cột thumbnail3
                rating = ?,          
                sold = ?,            -- Thêm cột sold
                color2 = ?           -- Thêm cột color2
            WHERE pid = ?";
    
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
        $conn->close();
        exit();
    }
    // title (s), thumbnail (s), price (d), stock (i), size (s), size2 (s), size3 (s), color (s), description (s), cid (i),
    // discount (d), thumbnail2 (s), thumbnail3 (s), rating (d), sold (i), color2 (s), pid (i)
    $stmt->bind_param("ssdississidssdisi", 
                        $title, $thumbnail, $price, $stock, 
                        $size, $size2, $size3, $color, $description, $cid,
                        $discount, $thumbnail2, $thumbnail3, $rating, $sold, $color2, $pid); 

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Product updated successfully.']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Product details are up to date (no changes made).']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating product: ' . $stmt->error]);
    }
    
    $stmt->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method. Only POST requests are allowed.']);
}

$conn->close();
?>