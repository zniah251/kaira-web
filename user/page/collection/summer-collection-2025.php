<!DOCTYPE html>
<html lang="en">
<?php
// Bắt đầu session để giữ sản phẩm không đổi khi load lại trang
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// connect.php
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

// --- ĐỊNH NGHĨA HÀM HIỂN THỊ SẢN PHẨM ---
function displayProductGrid($conn, $pid_list, $title = "Sản Phẩm")
{
    if (empty($pid_list)) {
        echo "<p class='text-center text-gray-600 col-span-full'>Không tìm thấy sản phẩm nào cho '$title'</p>";
        return;
    }

    $placeholders = implode(',', array_fill(0, count($pid_list), '?'));
    $sql = "SELECT pid, title, price, thumbnail FROM product 
            WHERE pid IN ($placeholders) 
            ORDER BY FIELD(pid, $placeholders)";

    $stmt = $conn->prepare($sql);
   $types = str_repeat('i', count($pid_list) * 2); // vì có 2 lần placeholder

$params = [];
$params[] = &$types;

// lần 1: WHERE IN
for ($i = 0; $i < count($pid_list); $i++) {
    $params[] = &$pid_list[$i];
}

// lần 2: ORDER BY FIELD
for ($i = 0; $i < count($pid_list); $i++) {
    $params[] = &$pid_list[$i];
}
    call_user_func_array([$stmt, 'bind_param'], $params);
    // -----------------------------------------

    $stmt->execute();
    $result = $stmt->get_result();

    $shown_titles = []; 

    if ($result->num_rows > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8">
            <?php while ($row = $result->fetch_assoc()):
                $row_title = mb_strtolower(trim($row['title']));
                if (in_array($row_title, $shown_titles)) continue; 
                $shown_titles[] = $row_title;

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
    <?php endif;
    $stmt->close();
}

// --- LOGIC LẤY NGẪU NHIÊN 1 LẦN (CHỈ TOPS & SKIRTS) ---
if (!isset($_SESSION['summer_pids_final'])) {
    // Tops: 13, Skirts: 16
    $res = $conn->query("SELECT pid FROM product WHERE cid IN (13, 16) ORDER BY RAND() LIMIT 8");
    $random_pids = [];
    while ($r = $res->fetch_assoc()) {
        $random_pids[] = (int)$r['pid'];
    }
    $_SESSION['summer_pids_final'] = $random_pids;
}

$all_pids = $_SESSION['summer_pids_final'];
$block1_pids = array_slice($all_pids, 0, 4);
$block2_pids = array_slice($all_pids, 4, 4);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summer Collection 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../../user/css/vendor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="abtus.css">
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="../../css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../css/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/e-web/user/css/tailwind-replacement.css">
    <style>
        body { font-family: 'Times New Roman', serif; color: #000; }
        h2, h3, h4 { font-family: 'Times New Roman', Times, serif !important; }
        .btn-primary { background-color: #434343; color: white; padding: 0.5rem; border-radius: 9999px; text-decoration: none; display: block; }
        .btn-primary:hover { background-color: #2f2f2f; color: white; }
        .product-title-ellipsis { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 100%; }
    </style>
</head>

<body class="bg-white">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/e-web/navbar.php'); ?>
    
    <div class="w-full max-w-[1200px] mx-auto px-6">
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

        <div class="p-8 bg-white mb-8">
            <h3 class="text-2xl font-bold text-gray-700">GIỚI THIỆU</h3>
            <p class="text-gray-700 mt-1">
                Chào mừng bạn đến với <strong>Summer Collection 2025</strong> - nơi hội tụ những thiết kế tinh tế nhất dành riêng cho phái đẹp trong mùa lễ hội rực rỡ này. 
                Chúng tôi hiểu rằng mùa hè không chỉ là cái nắng oi ả, mà còn là nguồn cảm hứng cho những phong cách phối đồ đầy phóng khoáng, nhẹ nhàng và thanh lịch.
            </p>
            <p class="text-gray-700 mt-3">
                Trong bộ sưu tập này, từng chất liệu cotton, linen và voan tơ đã được tuyển chọn kỹ lưỡng để mang lại cảm giác thoáng mát tối đa. Sự kết hợp hoàn hảo giữa những chiếc áo blouse bay bổng cùng chân váy bồng bềnh sẽ giúp nàng tự tin thể hiện cá tính riêng, dù là dạo phố, đi làm hay trong những chuyến du lịch xa xôi. Hãy để trang phục của chúng tôi cùng bạn viết nên những kỷ niệm mùa hè thật rạng rỡ và đáng nhớ.
            </p>
        </div>

        <div class="p-8 bg-white pt-0">
            <h4 class="text-2xl font-bold text-gray-700">Sản phẩm trong bộ sưu tập</h4>
            <?php displayProductGrid($conn, $block1_pids, "Sản phẩm trong bộ sưu tập"); ?>
        </div>

        <div class="p-8 bg-white pt-0">
            <h4 class="text-2xl font-bold text-gray-700">Sản phẩm gợi ý thêm</h4>
            <?php displayProductGrid($conn, $block2_pids, "Sản phẩm khác"); ?>
        </div>
    </div>

    <?php $conn->close(); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/e-web/footer.php'); ?>
</body>
</html>