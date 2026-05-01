<?php
// process_edit_category.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. KẾT NỐI CƠ SỞ DỮ LIỆU
    include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; // Điều chỉnh đường dẫn nếu cần

    if ($conn->connect_error) {
        header("Location: category-list.php?error=" . urlencode("Connection failed: " . $conn->connect_error));
        exit();
    }

    // 2. LẤY DỮ LIỆU TỪ FORM (bao gồm ID)
    $cid = $_POST['cid'] ?? null;
    $cname = $_POST['cname'] ?? '';
    $cslug = $_POST['cslug'] ?? null;
    $cfile = $_POST['cfile'] ?? null;
    $parentid = $_POST['parentid'] ?? null;
    $is_product_category = isset($_POST['is_product_category']) ? (int)$_POST['is_product_category'] : 0;

    // Validate ID
    if (!$cid || !is_numeric($cid)) {
        header("Location: category-list.php?error=" . urlencode("ID danh mục không hợp lệ để chỉnh sửa."));
        exit();
    }

    // Xử lý parentid: chuyển '' (nếu chọn "Select parent category") hoặc '0' (nếu chọn "-- Gốc --") thành NULL cho DB
    if ($parentid === '' || $parentid === '0') {
        $parentid = null;
    } else {
        $parentid = (int)$parentid;
    }

    // 3. VALIDATE DỮ LIỆU
    if (empty($cname)) {
        header("Location: category-list.php?error=" . urlencode("Tên danh mục không được để trống."));
        exit();
    }
    // (Thêm các validation khác nếu cần)

    // 4. CHUẨN BỊ VÀ THỰC THI CÂU LỆNH UPDATE
    $sql_update = "UPDATE category SET cname = ?, cslug = ?, cfile = ?, parentid = ?, is_product_category = ? WHERE cid = ?";
    
    $stmt = $conn->prepare($sql_update);

    if ($stmt === false) {
        header("Location: category-list.php?error=" . urlencode("Lỗi chuẩn bị câu lệnh SQL: " . $conn->error));
        exit();
    }

    // Bind các tham số: sssiii (string, string, string, integer, integer, integer)
    $stmt->bind_param("sssiii", $cname, $cslug, $cfile, $parentid, $is_product_category, $cid);

    if ($stmt->execute()) {
        header("Location: category-list.php?success=1"); // Hoặc thông báo "chỉnh sửa thành công"
        exit();
    } else {
        header("Location: category-list.php?error=" . urlencode("Lỗi khi cập nhật danh mục: " . $stmt->error));
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    // Nếu không phải là POST request, chuyển hướng về trang danh sách
    header("Location: category-list.php");
    exit();
}
?>