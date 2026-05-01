<?php
session_start(); // Bắt đầu session ở đầu file

// Kiểm tra xem người dùng đã đăng nhập chưa
// Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    // Điều chỉnh đường dẫn đến trang đăng nhập của bạn
    header("Location: /e-web/user/page/sign-in/login2.php"); 
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
$user_id = $_SESSION['uid']; // Lấy UID của người dùng từ session

$wishlist_products = []; // Khởi tạo mảng để chứa các sản phẩm yêu thích

// Đảm bảo $conn được khởi tạo từ connect.php
if (!isset($conn) || !$conn) {
    echo "<p class='text-red-500'>Lỗi kết nối cơ sở dữ liệu. Vui lòng kiểm tra file connect.php</p>";
    error_log('Database connection error in wishlist.php');
} else {
    // Lấy danh sách sản phẩm yêu thích của người dùng
    // JOIN bảng `product` với `wishlist`
    // Sử dụng `p.pid` (ID sản phẩm trong bảng product) và `w.pid` (ID sản phẩm trong bảng wishlist)
    // và `w.uid` (ID người dùng trong bảng wishlist)
    $stmt = $conn->prepare("
        SELECT p.pid, p.title, p.price, p.thumbnail
        FROM product p
        JOIN wishlist w ON p.pid = w.pid
        WHERE w.uid = ?
        ORDER BY w.create_at DESC
    ");
    if ($stmt === false) {
        echo "<p class='text-red-500'>Lỗi truy vấn CSDL: " . $conn->error . "</p>";
        error_log('MySQLi prepare error in wishlist.php: ' . $conn->error);
    } else {
        $stmt->bind_param('i', $user_id);
        if (!$stmt->execute()) {
            echo "<p class='text-red-500'>Lỗi thực thi truy vấn: " . $stmt->error . "</p>";
            error_log('MySQLi execute error in wishlist.php: ' . $stmt->error);
        } else {
            $result = $stmt->get_result();
            if ($result) {
                $wishlist_products = $result->fetch_all(MYSQLI_ASSOC);
            }
        }
        $stmt->close();
    }
}
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
        /* Reset và Base styles */
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
        
        /* Responsive Grid */
        @media (min-width: 640px) {
            .sm\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (min-width: 768px) {
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (min-width: 1024px) {
            .lg\:grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        @media (min-width: 1280px) {
            .xl\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
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
        .text-base { font-size: 1rem; }
        .font-semibold { font-weight: 600; }
        .font-medium { font-weight: 500; }
        .font-bold { font-weight: 700; }
        .text-gray-800 { color: #1f2937; }
        .text-gray-600 { color: #4b5563; }

        /* Background & Colors */
        .bg-white { background-color: white; }
        .bg-gray-100 { background-color: #f3f4f6; }

        /* Border & Shadow */
        .rounded-lg { border-radius: 0.5rem; }
        .rounded-xl { border-radius: 0.75rem; }
        .rounded { border-radius: 0.25rem; }
        .rounded-full { border-radius: 9999px; }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }

        /* Product Card Specific */
        .product-card {
            position: relative;
            transition: all 0.3s ease;
        }

        .product-card:hover .product-image-zoom {
            transform: scale(1.1);
        }
        
        .product-image-zoom {
            transform: scale(1.0);
            transition: all 0.3s ease-in-out;
            will-change: transform;
        }

        /* Image styles */
        .h-72 { height: 18rem; }
        .w-10 { width: 2.5rem; }
        .h-10 { height: 2.5rem; }
        .object-cover { object-fit: cover; }
        .w-full { width: 100%; }

        /* Button styles */
        .btn-primary {
            background-color: #434343;
            color: white;
            padding: 0.5rem;
            border-radius: 9999px;
            text-align: center;
            transition: background-color 0.2s;
            display: block;
            text-decoration: none;
        }
        .border {
            border: 1px solid #e5e7eb;
            text-decoration: none;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .hover\:bg-gray-200:hover {
            background-color: #e5e7eb;
        }

        /* Wishlist button */
        .btn-icon.btn-wishlist {
            position: absolute;
            top: 10px;
            right: 10px;
            background: white;
            border-radius: 50%;
            padding: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            z-index: 10;
        }

        .product-card:hover .btn-wishlist {
            opacity: 1;
            transform: translateY(0);
        }

        .btn-icon.btn-wishlist:hover {
            transform: scale(1.1);
            background: #f8f8f8;
        }

        .btn-icon.btn-wishlist svg {
            width: 24px;
            height: 24px;
            stroke: #ff4444;
            stroke-width: 2;
            fill: none;
            transition: fill 0.3s ease;
        }

        .btn-icon.btn-wishlist:hover svg {
            fill: #ff4444;
        }

        /* Text Ellipsis */
        .product-title-ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Sidebar Header */
        .sidebar-header {
            text-align: center;
            padding: 20px 0;
        }
        .sidebar-header h6 {
            text-align: center;
            margin: 0;
            font-weight: 600;
        }

    </style>
</head>

<body>
    <?php include('../../../navbar.php'); ?>
   <div class="flex min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #f1f1f0;">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/e-web/sidebar2.php'; ?>
        <div class="flex-1 bg-white p-6 rounded-lg shadow-md" style="margin: 20px 0;">
            <h3 class="text-2xl font-semibold mb-6 text-gray-800" style="border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 15px;">Sản phẩm yêu thích</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php if (empty($wishlist_products)): ?>
                    <p class="text-gray-600 col-span-full">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                    <a href="/e-web/user/page/woman/dresses.php" class="text-gray-500 hover:underline mt-4 inline-block col-span-full">Tiếp tục mua sắm</a>
                    <?php else: ?>
                    <?php foreach ($wishlist_products as $product): ?>
                        <div class="product-card bg-gray-100 rounded-xl p-3 flex flex-col shadow-sm">
                            <div class="relative overflow-hidden rounded mb-3">
                                <a href="../product_detail/product_detail.php?pid=<?php echo htmlspecialchars($product['pid']); ?>" class="block">
                                    <?php
                                    // Lấy đường dẫn ảnh từ CSDL
                                    $thumbnail = $product['thumbnail'];

                                    // Nếu đường dẫn bắt đầu bằng 'admin/assets/images/', loại bỏ phần đó
                                    if (strpos($thumbnail, 'admin/assets/images/') === 0) {
                                        $thumbnail = substr($thumbnail, strlen('admin/assets/images/'));
                                    }

                                    // Đảm bảo đường dẫn đúng và encode lại cho URL (đặc biệt với tên file có dấu tiếng Việt, khoảng trắng)
                                    $thumbnail_url = '/e-web/admin/assets/images/' . rawurlencode($thumbnail);
                                    ?>
                                    <img src="<?php echo htmlspecialchars($thumbnail_url); ?>" 
                                         alt="<?php echo htmlspecialchars($product['title']); ?>" 
                                         class="h-72 object-cover w-full product-image-zoom" />
                                </a>
                                <button class="btn-icon btn-wishlist active" data-product-id="<?php echo htmlspecialchars($product['pid']); ?>">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </button>
                            </div>
                            <h3 class="font-medium text-base mb-1 product-title-ellipsis"><?php echo htmlspecialchars($product['title']); ?></h3>
                            <p class="font-bold text-lg mb-2"><?php echo number_format($product['price'], 0, ',', '.'); ?>₫</p>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars($thumbnail_url); ?>" class="w-10 h-10 border rounded" />
                            </div>
                            <a href="../product_detail/product_detail.php?pid=<?php echo htmlspecialchars($product['pid']); ?>" class="btn-primary mb-2 text-center">Mua ngay</a>
                            <button class="border py-2 rounded-full hover:bg-gray-200">Thêm vào giỏ</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include('../../../footer.php'); ?>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Lắng nghe sự kiện click trên tất cả các nút wishlist trong trang
            document.querySelectorAll('.btn-wishlist').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a> (nếu có) hoặc button

                    const productId = this.dataset.productId; // Lấy product ID từ data attribute
                    const buttonElement = this; // Tham chiếu đến nút được click

                    if (!productId) {
                        alert("Không tìm thấy ID sản phẩm.");
                        return;
                    }

                    // Gửi yêu cầu AJAX để xóa sản phẩm khỏi wishlist
                    fetch('/e-web/user/page/ajax_handlers/toggle_wishlist.php', { // Điều chỉnh đường dẫn
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ productId: productId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.action === 'removed') {
                                alert("Sản phẩm đã được xóa khỏi danh sách yêu thích!");
                                // Xóa thẻ sản phẩm khỏi DOM khi đã xóa thành công
                                const productCard = buttonElement.closest('.product-card');
                                if (productCard) {
                                    productCard.remove();
                                }
                                // Kiểm tra nếu không còn sản phẩm nào, hiển thị thông báo "Bạn chưa có sản phẩm nào..."
                                const productGrid = document.querySelector('.grid');
                                if (productGrid && productGrid.children.length === 0) {
                                    productGrid.innerHTML = `
                                        <p class="text-gray-600 col-span-full">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                                        <a href="/e-web/user/page/woman/dresses.php" class="text-gray-500 hover:underline mt-4 inline-block col-span-full">Tiếp tục mua sắm</a>
                                    `;
                                }
                            } else if (data.action === 'added') {
                                // Đây là trường hợp không mong muốn trong trang wishlist (chỉ có xóa)
                                // Nhưng giữ lại để xử lý logic nếu có thay đổi trong tương lai
                                alert("Sản phẩm đã được thêm vào danh sách yêu thích (lẽ ra chỉ có xóa từ trang này).");
                            }
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error toggling wishlist:', error);
                        alert("Có lỗi xảy ra khi xử lý yêu thích. Vui lòng thử lại.");
                    });
                });
            });
        });
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.js"></script>
</body>

</body>