<?php
// process_add_category.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; // Điều chỉnh đường dẫn nếu cần


    // 2. LẤY DỮ LIỆU TỪ FORM
    $cname = $_POST['cname'] ?? '';
    $cslug = $_POST['cslug'] ?? null; // Có thể null nếu không nhập
    $cfile = $_POST['cfile'] ?? null; // cfile là text input
    $parentid = $_POST['parentid'] ?? null;
    $is_product_category = isset($_POST['is_product_category']) ? (int)$_POST['is_product_category'] : 0; // Mặc định là 0 nếu không chọn

    // Xử lý parentid: chuyển '' (nếu chọn "Select parent category") hoặc '0' (nếu chọn "-- Gốc --") thành NULL cho DB
    if ($parentid === '' || $parentid === '0') {
        $parentid = null;
    } else {
        $parentid = (int)$parentid; // Đảm bảo là số nguyên
    }

    // 3. VALIDATE DỮ LIỆU
    if (empty($cname)) {
        header("Location: category-list.php?error=" . urlencode("Tên danh mục không được để trống."));
        exit();
    }
    // Thêm các validation khác nếu cần:
    // - Kiểm tra cslug có hợp lệ không
    // - Kiểm tra trùng lặp cname/cslug
    // - Kiểm tra cfile có đúng định dạng không

    // 4. CHUẨN BỊ VÀ THỰC THI CÂU LỆNH INSERT
    // Sử dụng Prepared Statements để an toàn hơn và tránh SQL Injection
    $sql_insert = "INSERT INTO category (cname, cslug, cfile, parentid, is_product_category) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql_insert);

    if ($stmt === false) {
        header("Location: category-list.php?error=" . urlencode("Lỗi chuẩn bị câu lệnh SQL: " . $conn->error));
        exit();
    }

    // Bind các tham số: s (string), i (integer)
    // sssii: cname (string), cslug (string), cfile (string), parentid (integer), is_product_category (integer)
    $stmt->bind_param("sssii", $cname, $cslug, $cfile, $parentid, $is_product_category);

    if ($stmt->execute()) {
        header("Location: category-list.php?success=1");
        exit();
    } else {
        // Lỗi khi thực thi
        header("Location: category-list.php?error=" . urlencode("Lỗi khi thêm danh mục: " . $stmt->error));
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