<?php
session_start(); // Bắt đầu phiên làm việc để lấy UID

include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; // Đảm bảo đường dẫn đúng đến file connect.php

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['uid'])) {
    // Nếu chưa đăng nhập, chuyển hướng hoặc hiển thị thông báo lỗi
    header("Location: page/sign-in/login2.php"); // Chuyển hướng về trang đăng nhập
    exit;
}

$uid = $_SESSION['uid'];

// Truy vấn các voucher mà người dùng đã lưu
// Tham gia bảng user_voucher với bảng voucher để lấy thông tin chi tiết về voucher
$sql = "SELECT uv.uvid, v.name, v.discount, v.minprice, v.expiry, uv.getting_at, uv.status 
        FROM user_voucher uv
        JOIN voucher v ON uv.vid = v.vid
        WHERE uv.uid = $uid
        ORDER BY uv.getting_at DESC"; // Sắp xếp theo thời gian lưu mới nhất

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Kaira Shopping Cart</title>
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../../user/css/vendor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="abtus.css">
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="../../css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../css/swiper-bundle.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap"
        rel="stylesheet">
    <link href="/e-web/user/css/tailwind-replacement.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            color: black;
            /* Thêm fallback font */
        }
        h1, h2, h3, h4, h5 {
            font-family: 'Times New Roman', Times, serif !important;
            /* Sử dụng font Times New Roman cho tiêu đề */
        }

        /* Layout styles */
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .flex-1 { flex: 1 1 0%; }
        .grid { display: grid; }
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .gap-6 { gap: 1.5rem; }
        .gap-x-6 { column-gap: 1.5rem; }
        .gap-y-4 { row-gap: 1rem; }
        
        /* Responsive Grid */
        @media (min-width: 640px) {
            .sm\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
/* Spacing */
        .p-6 { padding: 1.5rem; }
        .p-3 { padding: 0.75rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-3 { margin-bottom: 0.75rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-1 { margin-bottom: 0.25rem; }
        /* Typography */
        .text-2xl { font-size: 1.5rem; }
        .text-lg { font-size: 1.125rem; }
        .text-sm { font-size: 0.875rem; }
        .font-semibold { font-weight: 600; }
        .voucher-item-card { /* Đổi tên class để tránh trùng với .voucher của bạn */
            display: flex;
            background-color: #fff6f6;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            font-family: 'Times New Roman', Times, serif;
            /* width: 100%; để nó tự căn chỉnh trong grid */
        }

        /* Răng cưa giả */
        .voucher-item-card::after {
            content: "";
            position: absolute;
            top: 0;
            right: 120px; /* Điều chỉnh vị trí đường kẻ ngang nếu layout thay đổi */
            width: 1px;
            height: 100%;
            border-left: 2px dashed #ff8c8c;
        }
        
        .voucher-left-content { /* Đổi tên class */
            flex: 1;
            padding: 20px;
        }

        .voucher-title-text { /* Đổi tên class */
            color: #d81b60;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .voucher-condition-text, /* Đổi tên class */
        .voucher-detail-text { /* Đổi tên class */
            color: #333;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .voucher-expire-text { /* Đổi tên class */
            color: #888;
            font-size: 13px;
            font-style: italic;
        }

        .voucher-right-action { /* Đổi tên class */
            width: 120px;
            background-color: #fff6f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            gap: 10px;
            text-align: center; /* Căn giữa nội dung phần phải */
        }

        .voucher-right-action i {
            font-size: 24px;
            color: #d81b60;
            margin-bottom: 5px; /* Thêm khoảng cách */
        }

        .voucher-status-text {
            font-weight: bold;
            text-transform: capitalize;
            margin-top: 10px;
            font-size: 14px;
        }
        .voucher-status-unused {
            color: green;
        }
        .voucher-status-used {
            color: orange;
        }
        .voucher-status-expired {
            color: red;
        }

        .use-voucher-btn {
            background-color: #ff6d6d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 13px;
            cursor: pointer;
            white-space: nowrap; /* Ngăn nút bị xuống dòng */
        }

        .use-voucher-btn:hover {
            background-color: #e94a4a;
        }

        .voucher-item-card.expired {
            opacity: 0.6; /* Làm mờ voucher đã hết hạn */
            filter: grayscale(80%); /* Chuyển sang ảnh xám */
        }
        .voucher-item-card.used {
            opacity: 0.8; /* Làm mờ voucher đã sử dụng nhẹ hơn */
        }
        .no-vouchers {
            text-align: center;
            color: #777;
            padding: 40px;
            border: 1px dashed #ccc;
            border-radius: 8px;
            grid-column: 1 / -1; /* Để nó chiếm toàn bộ chiều rộng của grid */
        }
    </style>
</head>
<body>
    <?php include('../../../navbar.php'); ?>
   <div class="flex min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #f1f1f0;">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/e-web/sidebar2.php'; ?>
        <div class="flex-1 bg-white p-6 rounded-lg shadow-md" style="margin: 20px 0;">
            <h3 class="text-2xl font-semibold mb-6 text-gray-800" style="border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 15px;">Voucher</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <?php 
                            // Xác định trạng thái voucher
                            $status_class = strtolower($row['status']); 
                            $status_text = 'Trạng thái: ' . $row['status'];
                            $is_expired = false;

                            $expiry_date = new DateTime($row['expiry']);
                            $current_date = new DateTime();

                            // Kiểm tra nếu voucher đã hết hạn và chưa được sử dụng
                            if ($expiry_date < $current_date && $row['status'] !== 'used') {
                                $status_text = 'Trạng thái: Đã hết hạn';
                                $status_class = 'expired';
                                $is_expired = true;
                            } else if ($row['status'] === 'used') {
                                $status_text = 'Trạng thái: Đã sử dụng';
                                $status_class = 'used';
                            } else {
                                $status_text = 'Trạng thái: Chưa sử dụng';
                                $status_class = 'unused';
                            }
                        ?>
                        <div class="voucher-item-card <?php echo $status_class; ?>">
                            <div class="voucher-left-content">
                                <h3 class="voucher-title-text"><?php echo htmlspecialchars($row['name']); ?></h3>
                                <?php if ($row['minprice'] > 0): ?>
                                    <p class="voucher-condition-text">Đơn hàng từ <?php echo number_format($row['minprice'], 0, ',', '.'); ?> VNĐ</p>
                                <?php endif; ?>
                                <p class="voucher-detail-text">
                                    Giảm 
                                    <?php 
                                        if ($row['discount'] > 0 && $row['discount'] <= 100) { // Giảm theo %
                                            echo $row['discount'] . '%';
                                        } else { // Giảm theo số tiền
                                            echo number_format($row['discount'], 0, ',', '.') . ' VNĐ';
                                        }
                                    ?>
                                </p>
                                <p class="voucher-expire-text"><em>HSD đến: <?php echo date('d-m-Y', strtotime($row['expiry'])); ?></em></p>
                                <p class="voucher-status-text voucher-status-<?php echo $status_class; ?>"><?php echo $status_text; ?></p>
                                <p class="voucher-expire-text"><em>Đã lưu vào: <?php echo date('d-m-Y H:i', strtotime($row['getting_at'])); ?></em></p>
                            </div>
                            <div class="voucher-right-action">
                                <?php if (strpos(strtolower($row['name']), 'freeship') !== false): ?>
                                    <i class="fa-solid fa-truck-fast"></i>
                                <?php elseif (strpos(strtolower($row['name']), 'discount') !== false): ?>
                                    <i class="fa-solid fa-money-bill"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-truck-fast"></i> <?php endif; ?>

                                <?php if ($status_class === 'unused'): ?>
                                    <button class="use-voucher-btn" data-uvid="<?php echo $row['uvid']; ?>">Sử dụng</button>
                                <?php elseif ($status_class === 'used'): ?>
                                    <span>Đã sử dụng</span>
                                <?php else: // expired ?>
                                    <span>Đã hết hạn</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-vouchers">
                        <p>Bạn chưa lưu voucher nào.</p>
                        <p><a href="/e-web/index.php" style="color: #007bff; text-decoration: underline;">Quay lại trang chủ để tìm voucher</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
     <?php include('../../../footer.php'); ?>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>
    