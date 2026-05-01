<!DOCTYPE html>
<html lang="en">
<?php
// connect.php
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
// --- ĐỊNH NGHĨA HÀM HIỂN THỊ SẢN PHẨM ---
// Hàm này sẽ nhận các tham số để tùy chỉnh truy vấn và tiêu đề
function displayProductGrid($conn, $category_id = null, $max_show = 4, $title = "Sản Phẩm", $exclude_pids = [])
{
    // Xây dựng câu truy vấn SQL
    $sql = "SELECT pid, title, price, thumbnail FROM product";
    $params = [];
    $types = "";

    if ($category_id !== null) {
        $sql .= " WHERE cid = ?";
        $params[] = $category_id;
        $types .= "i"; // 'i' cho integer
    }

    // Nếu có danh sách PID cần loại trừ (để tránh trùng lặp giữa các khối)
    if (!empty($exclude_pids)) {
        // Tạo chuỗi placeholders (?,?,?) cho IN clause
        $placeholders = implode(',', array_fill(0, count($exclude_pids), '?'));
        if ($category_id !== null) {
            $sql .= " AND pid NOT IN (" . $placeholders . ")";
        } else {
            $sql .= " WHERE pid NOT IN (" . $placeholders . ")";
        }

        foreach ($exclude_pids as $pid) {
            $params[] = $pid;
            $types .= "i";
        }
    }

    $sql .= " ORDER BY RAND() LIMIT ?"; // Lấy ngẫu nhiên, giới hạn số lượng
    $params[] = $max_show;
    $types .= "i";

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $shown_titles = []; // Để tránh trùng lặp tiêu đề trong chính khối này
    $current_block_pids = []; // Để lưu PID của sản phẩm trong khối này

    if ($result->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
            <?php while ($row = $result->fetch_assoc()):
                $row_title = mb_strtolower(trim($row['title']));
                if (in_array($row_title, $shown_titles)) continue; // Bỏ qua nếu tiêu đề trùng lặp trong khối hiện tại
                $shown_titles[] = $row_title;
                $current_block_pids[] = $row['pid']; // Lưu PID để loại trừ cho các khối sau

                // Xử lý đường dẫn ảnh
                $img = $row['thumbnail'];
                if (strpos($img, 'admin/assets/images/') === 0) {
                    $img = substr($img, strlen('admin/assets/images/'));
                }
                $img_url = '/e-web/admin/assets/images/' . rawurlencode(trim($img));
            ?>
                <div class="bg-gray-100 rounded-xl p-3 flex flex-col">
                    <a href="/e-web/user/page/product_detail/product_detail.php?pid=<?= htmlspecialchars($row['pid']) ?>">
                        <img src="<?= $img_url ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="rounded mb-3 h-72 object-cover w-full" />
                    </a>
                    <h3 class="font-medium text-sm mb-1 product-title-ellipsis"><?= htmlspecialchars($row['title']) ?></h3>
                    <p class="font-bold text-lg mb-2"><?= number_format($row['price'], 0, ',', '.') ?>₫</p>
                    <div class="mb-2">
                        <img src="<?= $img_url ?>" class="w-10 h-10 border rounded object-cover" />
                    </div>
                    <a href="/e-web/user/page/product_detail/product_detail.php?pid=<?= htmlspecialchars($row['pid']) ?>" class="btn-primary mb-2 text-center">Mua ngay</a>
                    <button class="border py-2 rounded-full hover:bg-gray-200">Thêm vào giỏ</button>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class='text-center text-gray-600 col-span-full'>Không tìm thấy sản phẩm nào cho "<?= htmlspecialchars($title) ?>"</p>
<?php endif;

    $stmt->close();
    return $current_block_pids; // Trả về danh sách PID đã hiển thị
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summer Collection 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../../user/css/vendor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="abtus.css">
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="../../css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../css/swiper-bundle.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <!-- MDB -->


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/e-web/user/css/tailwind-replacement.css">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            color: #000;
            /* Thêm fallback font */
        }
        h2, h3, h4 {
            font-family: 'Times New Roman', Times, serif !important;
            /* Sử dụng font Times New Roman cho tiêu đề */
        }
        .btn-primary {
            background-color: #434343;
            color: white;
            padding: 0.5rem;
            border-radius: 9999px;

        }

        .btn-primary:hover {
            background-color: #2f2f2f;
        }

        .size-btn {
            padding: 8px 16px;
            border: 1px solid transparent;
            border-radius: 9999px;
            /* bo tròn */
            background-color: #f9f9f9;
            font-weight: 500;
            cursor: pointer;
            transition: border 0.2s;
        }

        .size-btn.selected {
            border: 1px solid black;
            background-color: white;
        }

        .size-btn:hover {
            border-color: #666;
        }

        /* Modal */
        .size-modal {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-title-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            max-width: 100%;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.2);
            /* Lớp phủ mờ nhẹ để chữ nổi bật */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .banner-text {
            font-family: 'Georgia', serif;
            /* Hoặc một font script/decorative nếu muốn giống hơn */
            font-size: 3.5rem;
            /* Kích thước chữ lớn */
            color: #ffffff;
            /* Màu chữ trắng */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            /* Tạo bóng chữ */
            z-index: 10;
        }
    </style>
</head>

<body class="bg-white">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/e-web/navbar.php'); ?>
    
    <div class="w-full max-w-[1200px] mx-auto px-6">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-600 p-4">
            <a href="../../index.html" class="hover:underline">Trang chủ</a> &gt;
            <a href="" class="hover:underline">Sản phẩm</a> &gt;
            <a href="" class="hover:underline">Summer Collection 2025</a>
        </nav>
        <div class="relative w-full overflow-hidden rounded-lg shadow-lg mb-8">
            <img src="/e-web/user/images/collectionsummer.jpg"
                alt="Summer Collection 2025 Banner"
                class="w-full h-auto object-cover max-h-[500px]">

        </div>

        <h2 class="text-4xl font-bold text-gray-800 mb-8 uppercase text-center">
            SUMMER COLLECTION 2025
        </h2>

        <div class="p-8 bg-white  mb-8">
            <h3 class="text-2xl font-bold text-gray-700">GIỚI THIỆU</h3>
            <p class="text-gray-700 mt-1">Khám phá những thiết kế mới nhất cho mùa hè năm 2025, với các chất liệu thoáng mát và màu sắc rực rỡ, mang đến sự thoải mái và phong cách cho mọi hoạt động dưới ánh nắng.</p>
        </div>
        <div class="p-8 bg-white pt-0">
            <h4 class="text-2xl font-bold text-gray-700">Sản phẩm trong bộ sưu tập</h4>
        </div>
        <?php
        // Biến này để lưu các PID đã được hiển thị để tránh trùng lặp giữa các khối khác nhau
        $displayed_pids_global = [];
        // Lần 1: Hiển thị 4 sản phẩm cid=2
        $displayed_pids_global = array_merge(
            $displayed_pids_global,
            displayProductGrid($conn, 2, 4, "Sản phẩm trong bộ sưu tập", $displayed_pids_global)
        );

        // Lần 2: Hiển thị 4 sản phẩm cid=2, loại trừ các sản phẩm đã hiển thị ở lần 1
        $displayed_pids_global = array_merge(
            $displayed_pids_global,
            displayProductGrid($conn,2, 4, "Sản phẩm khác", $displayed_pids_global)
        );
        ?>

    </div>

    <?php
    // Đóng kết nối cơ sở dữ liệu
    $conn->close();
    ?>
    <!--footer-->
        <?php include('../../../footer.php'); ?>
    </div>
</body>
