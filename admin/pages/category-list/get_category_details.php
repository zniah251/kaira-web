<?php
// get_category_details.php

header('Content-Type: application/json'); // Thiết lập header để trả về JSON

// 1. KẾT NỐI CƠ SỞ DỮ LIỆU
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; // Điều chỉnh đường dẫn nếu cần

$response = ['error' => ''];

// 2. LẤY ID TỪ REQUEST
$categoryId = $_GET['id'] ?? null;

if (!$categoryId || !is_numeric($categoryId)) {
    $response['error'] = 'ID danh mục không hợp lệ.';
    echo json_encode($response);
    $conn->close();
    exit();
}

// 3. TRUY VẤN CƠ SỞ DỮ LIỆU
$sql = "SELECT cid AS id, cname AS category_name, cslug AS slug, cfile AS file, parentid, is_product_category FROM category WHERE cid = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    $response['error'] = 'Lỗi chuẩn bị câu lệnh SQL: ' . $conn->error;
    echo json_encode($response);
    $conn->close();
    exit();
}

$stmt->bind_param("i", $categoryId); // "i" cho integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $categoryData = $result->fetch_assoc();
    echo json_encode($categoryData); // Trả về dữ liệu dưới dạng JSON
} else {
    $response['error'] = 'Không tìm thấy danh mục.';
    echo json_encode($response);
}

$stmt->close();
$conn->close();
?>