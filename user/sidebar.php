<?php
// include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; 
// Bắt đầu session nếu nó chưa được bắt đầu ở các file gọi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Lấy tên người dùng từ session
$display_name = "Khách"; // Tên mặc định nếu chưa đăng nhập
if (isset($_SESSION['uname'])) { // Giả sử 'uname' được lưu trong session sau khi đăng nhập
    $display_name = htmlspecialchars($_SESSION['uname']);
}
?>

<head>
    <style>
h2, h3, h6 {
            font-family: 'Times New Roman', Times, serif !important;
            /* Sử dụng font Times New Roman cho tiêu đề */
        }
        /* Styling cho sidebar để khớp với hình ảnh */
        .sidebar {
            width: 250px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin: 20px 20px 20px 20px;
            /* Space between sidebar and content */
        }

        .sidebar-header {
            text-align: center;
            padding: 20px 0;
        }

        .sidebar-header h6 {
            text-align: center;
            margin: 0;
            font-weight: 600;
        }

        .sidebar-menu .list-group-item {
            border: none;
            padding: 12px 15px;
            font-size: 1rem;
            color: #495057;
            transition: background-color 0.2s ease;
        }

        .sidebar-menu .list-group-item.active {
            background-color: #f1f1f0;
            /* Primary color */
            color: #2f2f2f;
            border-radius: 5px;
        }

        .sidebar-menu .list-group-item:hover:not(.active) {
            background-color: #e9ecef;
            border-radius: 5px;
        }

        .sidebar-menu .list-group-item i {
            margin-right: 10px;
            width: 20px;
            /* Align icons */
            text-align: center;
        }
    </style>
</head>
<div class="sidebar">
    <div class="sidebar-header" style="border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 15px; margin-bottom: 15px; text-align: center;">
        <div style="width: 100%;">
            <h6 class="mb-0" style="text-align: center; font-weight: 600; "><?php echo $display_name; ?></h6>
        </div>
    </div>
    <div class="sidebar-menu list-group list-group-flush">
        <a href="/e-web/user/page/users/manage_orders.php" class="list-group-item list-group-item-action" data-path="manage_orders.php">
            <i class="fas fa-file-invoice"></i> Quản lý đơn hàng
        </a>
        <a href="/e-web/user/page/users/wishlist.php" class="list-group-item list-group-item-action" data-path="wishlist.php">
            <i class="fas fa-heart"></i> Sản phẩm yêu thích
        </a>
        <a href="/e-web/user/page/users/info.php" class="list-group-item list-group-item-action" data-path="info.php">
            <i class="fas fa-user"></i> Thông tin tài khoản
        </a>
        <a href="/e-web/user/page/users/voucher.php" class="list-group-item list-group-item-action" data-path="vouchers.php">
            <i class="fas fa-ticket-alt"></i> Ví voucher
        </a>
        <a href="/e-web/user/page/sign-in/logout.php" class="list-group-item list-group-item-action" data-path="logout.php">
            <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </a>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebarLinks = document.querySelectorAll('.sidebar-menu .list-group-item');
        const currentPath = window.location.pathname; // Lấy đường dẫn hiện tại

        sidebarLinks.forEach(link => {
            // Lấy đường dẫn từ href của liên kết
            const linkHref = link.getAttribute('href');
            // Lấy tên file từ đường dẫn (ví dụ: /e-web/user/page/users/info.php -> info.php)
            const linkFileName = linkHref.substring(linkHref.lastIndexOf('/') + 1);

            // So sánh tên file của liên kết với tên file của trang hiện tại
            if (currentPath.includes(linkFileName) && linkFileName !== '') {
                // Xóa class active khỏi tất cả các liên kết trước
                sidebarLinks.forEach(item => item.classList.remove('active'));
                // Thêm class active vào liên kết hiện tại
                link.classList.add('active');
            }
        });
    });
</script>