<?php
$search_query = isset($_GET['s']) ? $_GET['s'] : '';
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

// Chống SQL Injection
$search_query_safe = $conn->real_escape_string($search_query);

// Truy vấn sản phẩm theo title
$sql = "SELECT pid, title FROM product WHERE title LIKE '%" . $search_query_safe . "%' LIMIT 1"; // Tìm 1 sản phẩm khớp nhất
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_pid = $row['pid'];
    // Chuyển hướng đến trang chi tiết sản phẩm
    header("Location: /e-web/user/page/product_detail/product_detail.php?pid=" . $product_pid);
    exit(); // Rất quan trọng để dừng việc thực thi script
} else {
    // Nếu không tìm thấy sản phẩm nào
    echo "Không tìm thấy sản phẩm nào với từ khóa: " . htmlspecialchars($search_query);
    // Hoặc bạn có thể chuyển hướng về trang tìm kiếm với thông báo lỗi
    // header("Location: search_results.php?s=" . urlencode($search_query) . "&notfound=1");
    // exit();
}

$conn->close();
?>