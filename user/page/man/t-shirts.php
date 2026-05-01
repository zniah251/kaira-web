<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Dòng này cần phải ở đầu file PHP, trước mọi output khác.
}
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
// Lấy tên file hiện tại
$current_file = basename($_SERVER['PHP_SELF']);
// Lấy category hiện tại theo cfile
$sql_cat = "SELECT cname FROM category WHERE cfile = ?";
$stmt_cat = $conn->prepare($sql_cat);
$stmt_cat->bind_param("s", $current_file);
$stmt_cat->execute();
$stmt_cat->bind_result($category_name);
$stmt_cat->fetch();
$stmt_cat->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Áo sơ mi nam</title>
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
    <link href="/e-web/user/css/tailwind-replacement.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            color: black;
            /* Thêm fallback font */
        }
        h2, h3, h4 {
            font-family: 'Times New Roman', Times, serif !important;
            /* Sử dụng font Times New Roman cho tiêu đề */
        }
    </style>
</head>

<body>

    <?php include('../../../navbar.php'); ?>

    <div class="w-full max-w-[1200px] mx-auto px-6">
        <nav class="text-sm text-gray-600 mb-4 me-2 pt-3">
            <a href="../../index.html" class="hover:underline">Trang chủ</a> &gt;
            <a href="" class="hover:underline">Sản phẩm</a> &gt;
            <a href="" class="hover:underline"><?= htmlspecialchars($category_name ?? "Danh mục") ?></a>
        </nav>
        <div class="w-full mb-6 tabs-container">
            <div class="flex space-x-8 text-sm font-medium">
                <?php
                // Lấy slug của danh mục hiện tại từ query string
                $current_category_slug = isset($_GET['category']) ? $_GET['category'] : '';

                // Lấy dữ liệu từ bảng category
                $sql = "SELECT * FROM category WHERE parentid = 7";
                $result = $conn->query($sql);

                // Kiểm tra xem có dữ liệu trả về hay không
                if ($result->num_rows > 0) {
                    // Lặp qua các dòng dữ liệu
                    while ($row = $result->fetch_assoc()) {
                        $link_class = "tab-link";
                        // Nếu slug của category hiện tại khớp với slug của category trong vòng lặp, áp dụng class active
                        // Kiểm tra nếu cfile của category hiện tại trùng với tên file đang xem thì active
                        $current_file = basename($_SERVER['PHP_SELF']);
                        if ($row['cfile'] === $current_file) {
                            $link_class .= " tab-active"; // Active state
                        }

                        // Xây dựng đường dẫn
                        $base_path = dirname($_SERVER['PHP_SELF']) . '/';
                        $href = $base_path . htmlspecialchars($row['cfile']);
                ?>
                        <a href="<?= $href ?>" class="<?= $link_class ?>">
                            <?= htmlspecialchars($row['cname']) ?>
                        </a>
                <?php
                    }
                } else {
                    echo "Không có dữ liệu.";
                }
                ?>
            </div>
        </div>
        <div class="d-flex justify-content-start align-items-center mb-4 mt-4">
            <label for="sort-options" class="form-label mb-1 me-2">Sắp xếp theo:</label>
            <select id="sort-options" class="form-select" style="width: 200px; border-radius: 8px;" onchange="sortBy(this.value)">
                <option value="price-asc">Giá từ thấp đến cao</option>
                <option value="price-desc">Giá từ cao xuống thấp</option>
            </select>
        </div>

        <div class="row g-4" id="product-list">
            <?php
            $sql = "SELECT * FROM product WHERE cid = 11";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $img_url = "/e-web/admin/assets/images/" . rawurlencode($row['thumbnail']);
            ?>
                    <div class="col-md-3 product" data-price="<?= htmlspecialchars($row['price']) ?>">
                        <div class="bg-gray-100 rounded-xl p-3 flex flex-col">
                            <a href="/e-web/user/page/product_detail/product_detail.php?pid=<?= htmlspecialchars($row['pid']) ?>">
                                <img src="<?= $img_url ?>" alt="<?= htmlspecialchars($row['title']) ?>" class="rounded mb-3 h-72 object-cover w-full"
                                    onerror="this.src='/e-web/admin/assets/images/default.jpg'" />
                            </a>
                            <h3 class="font-medium text-sm mb-1 product-title-ellipsis"><?= htmlspecialchars($row['title']) ?></h3>
                            <p class="font-bold text-lg mb-2"><?= number_format($row['price'], 0, ',', '.') ?>₫</p>
                            <div class="mb-2">
                                <img src="<?= $img_url ?>" class="w-10 h-10 border rounded object-cover" />
                            </div>
                            <a href="/e-web/user/page/product_detail/product_detail.php?pid=<?= htmlspecialchars($row['pid']) ?>" class="btn-primary mb-2 text-center">Mua ngay</a>
                            <a href="/e-web/user/page/product_detail/product_detail.php?pid=<?= htmlspecialchars($row['pid']) ?>" class="border py-2 rounded-full hover:bg-gray-200 block text-center">Thêm vào giỏ</a>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p>Chưa có sản phẩm.</p>';
            }
            $conn->close();
            ?>
        </div>
    </div>

    <?php include('../../../footer.php'); ?>

    <script>
        function sortBy(option) {
            const container = document.getElementById('product-list');
            const products = Array.from(container.getElementsByClassName('product'));
            products.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price'));
                const priceB = parseFloat(b.getAttribute('data-price'));
                return option === 'price-asc' ? priceA - priceB : priceB - priceA;
            });
            products.forEach(p => container.appendChild(p));
        }

        function swapImage(thumb) {
            const mainImg = thumb.closest('.product-card').querySelector('.main-img');
            mainImg.src = thumb.src;
        }
    </script>
</body>

</html>