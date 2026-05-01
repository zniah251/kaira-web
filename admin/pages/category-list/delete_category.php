<?php
// delete_category.php

// 1. KẾT NỐI CƠ SỞ DỮ LIỆU
// Sử dụng file connect.php đã có
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; // Điều chỉnh đường dẫn nếu cần

if ($conn->connect_error) {
    header("Location: category-list.php?error=" . urlencode("Connection failed: " . $conn->connect_error));
    exit();
}

// 2. LẤY ID TỪ URL
$categoryId = $_GET['id'] ?? null;

// 3. VALIDATE ID
if (!$categoryId || !is_numeric($categoryId)) {
    header("Location: category-list.php?error=" . urlencode("ID danh mục không hợp lệ để xóa."));
    exit();
}

// 4. CHUẨN BỊ VÀ THỰC THI CÂU LỆNH DELETE
// Sử dụng Prepared Statements để an toàn hơn và tránh SQL Injection
$sql_delete = "DELETE FROM category WHERE cid = ?";

$stmt = $conn->prepare($sql_delete);

if ($stmt === false) {
    header("Location: category-list.php?error=" . urlencode("Lỗi chuẩn bị câu lệnh SQL: " . $conn->error));
    exit();
}

// Bind tham số: "i" cho integer (kiểu dữ liệu của cid)
$stmt->bind_param("i", $categoryId);

if ($stmt->execute()) {
    // Xóa thành công
    header("Location: category-list.php?success=1&message=" . urlencode("Danh mục đã được xóa thành công."));
    exit();
} else {
    // Lỗi khi thực thi
    // Kiểm tra lỗi nếu có ràng buộc khóa ngoại (foreign key constraint)
    if ($conn->errno == 1451) { // Mã lỗi MySQL cho Foreign Key Constraint
        header("Location: category-list.php?error=" . urlencode("Không thể xóa danh mục này vì có sản phẩm hoặc danh mục con đang phụ thuộc vào nó. Vui lòng xóa các mục liên quan trước."));
    } else {
        header("Location: category-list.php?error=" . urlencode("Lỗi khi xóa danh mục: " . $stmt->error));
    }
    exit();
}

$stmt->close();
$conn->close();

?>