<?php
session_start();
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    // Điều chỉnh đường dẫn đến trang đăng nhập của bạn
    header("Location: /e-web/user/page/sign-in/login2.php");
    exit();
}

// Kiểm tra quyền admin
if (!isset($_SESSION['rid']) || $_SESSION['rid'] != 1) {
    // Nếu không phải admin, chuyển hướng về trang chủ
    header("Location: /e-web/user/index.php");
    exit();
}
// Bao gồm file kết nối cơ sở dữ liệu
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

$where_clause = "";
$where_params = [];
$where_types = "";

if (isset($_GET['search_name']) && $_GET['search_name'] !== '') {
    $search_name = '%' . $_GET['search_name'] . '%';
    // Đảm bảo WHERE clause được xây dựng đúng cách
    if (empty($where_clause)) {
        $where_clause .= " WHERE u.uname LIKE ?";
    } else {
        $where_clause .= " AND u.uname LIKE ?";
    }
    $where_params[] = $search_name;
    $where_types .= "s";
}
// 1. Xử lý tham số phân trang
$allowed_limits = [10, 25, 50, 100];
$limit = 25;
if (isset($_GET['limit']) && is_numeric($_GET['limit']) && in_array(intval($_GET['limit']), $allowed_limits)) {
    $limit = intval($_GET['limit']);
}
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// 2. Lọc theo danh mục nếu có
$filter_cid = null;
$where_clause = "";
$where_params = [];
$where_types = "";
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $filter_cid = intval($_GET['category_id']);
    $where_clause = " WHERE p.cid = ?";
    $where_types = "i";
    $where_params[] = $filter_cid;
}

// 3. Truy vấn tổng số sản phẩm (có filter)
$total_products_sql = "SELECT COUNT(p.pid) AS total_count FROM product p" . $where_clause;
$stmt_total = $conn->prepare($total_products_sql);
if (!empty($where_params)) {
    $stmt_total->bind_param($where_types, ...$where_params);
}
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total_count'];
$stmt_total->close();

// 4. Tính tổng số trang
$total_pages = max(1, ceil($total_products / $limit));

// 5. Đảm bảo trang hiện tại không vượt quá tổng số trang
if ($page > $total_pages && $total_pages > 0) {
    $page = $total_pages;
    $offset = ($page - 1) * $limit;
} elseif ($page < 1) {
    $page = 1;
    $offset = 0;
}

// 6. Tính chỉ số hiển thị
$start_entry = $total_products > 0 ? $offset + 1 : 0;
$end_entry = min($offset + $limit, $total_products);

// 7. Truy vấn danh sách sản phẩm (có phân trang + filter)
$sql_products = "SELECT 
                    p.pid, 
                    p.title, 
                    p.thumbnail, 
                    p.price, 
                    p.stock, 
                    p.size, 
                    p.color,
                    c.cname as category_name
                FROM 
                    product p
                JOIN 
                    category c ON p.cid = c.cid" .
    $where_clause . "
                ORDER BY 
                    p.pid DESC
                LIMIT ?, ?";

$stmt_products = $conn->prepare($sql_products);

if (!empty($where_params)) {
    $stmt_products->bind_param("iii", $filter_cid, $offset, $limit); // Có filter category
} else {
    $stmt_products->bind_param("ii", $offset, $limit); // Không có filter
}

$stmt_products->execute();
$result_products = $stmt_products->get_result();

$products = [];
if ($result_products) {
    while ($row_product = $result_products->fetch_assoc()) {
        $products[] = $row_product;
    }
} else {
    error_log("Lỗi truy vấn sản phẩm: " . $conn->error);
}
// Lấy tất cả danh mục để hiển thị cho select filter
$sql_categories = "SELECT cid, cname FROM category WHERE is_product_category = 1 ORDER BY cname ASC";
$result_categories = $conn->query($sql_categories);

$categories = []; // Khởi tạo mảng $categories
if ($result_categories) { // Luôn kiểm tra $result_categories có hợp lệ không
    if ($result_categories->num_rows > 0) {
        while ($row_cat = $result_categories->fetch_assoc()) {
            $categories[] = $row_cat;
        }
    }
}
$stmt_products->close();
// $conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Corona Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../../admin/template/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../../admin/template/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../template/assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="../../template/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../../template/assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="../../template/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- Layout styles -->

    <link rel="stylesheet" href="/e-web/admin/template/assets/css/style.css">

    <!-- End layout styles -->
    <link rel="shortcut icon" href="../../../admin/template/assets/images/favicon.png" />
    <!-- Sử dụng liên kết CDN mới nhất của Font Awesome -->

    <!-- Thêm vào trước thẻ </body> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.7.0/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            /* Thêm fallback font */
        }

        /* Đảm bảo chữ trong label cũng là màu đen nếu chúng mặc định không phải vậy */
        #productDetailModal label.form-label {
            font-weight: bold;
            /* Hoặc một màu tối phù hợp, tùy thuộc vào màu nền label hiện tại */
        }

        /* Có thể bạn cũng muốn chỉnh màu chữ của tiêu đề modal */
        #productDetailModal .modal-header .modal-title {
            font-weight: bold;
            /* Hoặc màu tối phù hợp */
        }

        /* Màu nền cho toàn bộ modal content */
        #productDetailModal .modal-content {
            background-color: #A59AC0;
            /* Màu nền mới */
        }

        /* Màu nền và màu chữ cho input, textarea, select trong modal */
        #productDetailModal input.form-control,
        #productDetailModal textarea.form-control,
        #productDetailModal select.form-control {
            background-color: #DCD7D5;
            /* Màu nền cho input/textarea/select */
            color: black !important;
            /* Màu chữ đen */
        }

        /* Chỉnh màu nền cho các nút phân trang */
        .pagination .page-item.active .page-link {
            background-color: #6366F1;
            /* Màu hơi tối hơn cho trạng thái active */
            border-color: #6366F1;
            /* Đồng bộ màu viền */
            color: white;
            /* Giữ màu chữ trắng */
        }

        .pagination .page-item .page-link {
            color: white;
            /* Màu chữ trắng để dễ nhìn trên nền tối */
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <?php include('../../template/sidebar.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include('../../template/navbar.php'); ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row ">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">

                                    <div class="row align-items-center mb-4">
                                        <div class="col">
                                            <input type="text" id="searchProduct" class="form-control todo-list-input" placeholder="Search product" style="color:white;">
                                        </div>
                                        <div class="col">
                                            <select class="form-control form-select" id="categoryFilter" style="color: #fff;">
                                                <option value="">Tất cả danh mục</option>
                                                <?php foreach ($categories as $cat): ?>
                                                    <option value="<?= htmlspecialchars($cat['cid']) ?>"
                                                        <?= (isset($filter_cid) && $filter_cid == $cat['cid']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($cat['cname']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select class="form-control">
                                                <option>Category1</option>
                                                <option>Category2</option>
                                                <option>Category3</option>
                                                <option>Category4</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center mb-3 border-bottom">
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Select số lượng sản phẩm -->
                                            <select class="btn btn-secondary dropdown-toggle" id="productPerPage" style="text-align: center; text-align-last: center;">
                                                <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                                                <option value="25" <?php if ($limit == 25) echo 'selected'; ?>>25</option>
                                                <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
                                                <option value="100" <?php if ($limit == 100) echo 'selected'; ?>>100</option>
                                            </select>
                                            <!-- Nút Export -->
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="mdi mdi-logout"></i> Export
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                                    <a class="dropdown-item" href="#"><span class="mdi mdi-printer"></span> Print</a>
                                                    <a class="dropdown-item" href="#" onclick="exportTableToExcel('orders.xls')">
                                                        <span class="mdi mdi-file-excel"></span> Excel
                                                    </a>
                                                    <a class="dropdown-item" href="#" onclick="exportTableToPDF()">
                                                        <span class="mdi mdi-file-pdf"></span> Pdf
                                                    </a>
                                                    <a class="dropdown-item" href="#"><span class="mdi mdi-content-copy"></span> Copy</a>
                                                </div>
                                            </div>
                                            <!-- Nút Add Product -->
                                            <a href="/e-web/admin/pages/add-products/product.php" class="btn" style="background:#6366f1;color:#fff;font-weight:500;box-shadow:0 2px 8px 0 #7b7bff33;">
                                                <i class="mdi mdi-plus"></i> Add Product
                                            </a>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>
                                                        <div class="form-check form-check-muted m-0">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input"></label>
                                                        </div>
                                                    </th>
                                                    <th class="fw-bold">ID</th>
                                                    <th class="fw-bold">PRODUCT</th>
                                                    <th class="fw-bold">CATEGORY</th>
                                                    <th class="fw-bold">PRICE</th>
                                                    <th class="fw-bold">STOCK</th>
                                                    <th class="fw-bold">SIZE</th>
                                                    <th class="fw-bold">COLOR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Kiểm tra xem mảng $products có dữ liệu không
                                                if (!empty($products)) {
                                                    foreach ($products as $product) {
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#productDetailModal" data-product-id="<?php echo htmlspecialchars($product['pid']); ?>">
                                                                    <i class="mdi mdi-plus"></i>
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <div class="form-check form-check-muted m-0">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input">
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($product['pid']); ?></td>
                                                            <td>
                                                                <?php
                                                                $img = $product['thumbnail'];
                                                                // Nếu đường dẫn bắt đầu bằng 'admin/assets/images/', loại bỏ phần này
                                                                if (strpos($img, 'admin/assets/images/') === 0) {
                                                                    $img = substr($img, strlen('admin/assets/images/'));
                                                                }
                                                                // Đảm bảo không có khoảng trắng và mã hóa URL
                                                                $img_url = '/e-web/admin/assets/images/' . rawurlencode(trim($img));
                                                                ?>
                                                                <img src="<?php echo $img_url; ?>" alt="Thumbnail" style="width:40px;height:40px;object-fit:cover;border-radius:0;">
                                                                <span class="ps-2"><?php echo htmlspecialchars($product['title']); ?></span>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                                            <td><?php echo htmlspecialchars(number_format($product['price'])); ?></td>
                                                            <td><?php echo htmlspecialchars($product['stock']); ?></td>
                                                            <td><?php echo htmlspecialchars($product['size']); ?></td>
                                                            <td><?php echo htmlspecialchars($product['color']); ?></td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    // Hiển thị thông báo nếu không có sản phẩm nào
                                                    echo '<tr><td colspan="10" class="text-center">Không có sản phẩm nào được tìm thấy.</td></tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <nav aria-label="Product list pagination">
                                            <ul class="pagination mb-0">
                                                <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?><?php echo ($filter_cid !== null ? '&category_id=' . $filter_cid : ''); ?>" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>

                                                <?php
                                                // Hiển thị các nút số trang
                                                $start_page = max(1, $page - 2);
                                                $end_page = min($total_pages, $page + 2);

                                                if ($end_page - $start_page + 1 < 5 && $total_pages > 5) {
                                                    if ($page <= 3) {
                                                        $end_page = min($total_pages, 5);
                                                        $start_page = 1;
                                                    } elseif ($page >= $total_pages - 2) {
                                                        $start_page = max(1, $total_pages - 4);
                                                        $end_page = $total_pages;
                                                    }
                                                }

                                                for ($i = $start_page; $i <= $end_page; $i++) {
                                                    $active_class = ($i == $page) ? 'active' : '';
                                                ?>
                                                    <li class="page-item <?php echo $active_class; ?>">
                                                        <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?><?php echo ($filter_cid !== null ? '&category_id=' . $filter_cid : ''); ?>">
                                                            <?php echo $i; ?>
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                                ?>

                                                <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?><?php echo ($filter_cid !== null ? '&category_id=' . $filter_cid : ''); ?>" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Product Details -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header align-items-center">
                    <h5 class="modal-title fs-4 fw-bold" id="productDetailModalLabel">DETAILS OF <span id="modalProductName">Product Name</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="productDetailModalBody">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex flex-column align-items-center mb-3">
                                <img id="modalProductThumbnail" src="" alt="Thumbnail" class="img-fluid rounded-3 mb-2" style="width: 100px;">
                                <label for="editThumbnail" class="form-label fs-6 fw-bold">Thumbnail 1:</label>
                                <input type="text" class="form-control form-control-lg" id="editThumbnail" name="edit_thumbnail" style="color: white;">
                            </div>
                            
                            <div class="d-flex flex-column align-items-center mb-3">
                                <img id="modalProductThumbnail2" src="" alt="Thumbnail2" class="img-fluid rounded-3 mb-2" style="width: 100px;">
                                <label for="editThumbnail2" class="form-label fs-6 fw-bold">Thumbnail 2:</label>
                                <input type="text" class="form-control form-control-lg" id="editThumbnail2" name="edit_thumbnail" style="color: white;">
                            </div>
                            
                            <div class="d-flex flex-column align-items-center mb-3">
                                <img id="modalProductThumbnail3" src="" alt="Thumbnail3" class="img-fluid rounded-3 mb-2" style="width: 100px;">
                                <label for="editThumbnail3" class="form-label fs-6 fw-bold">Thumbnail 3:</label>
                                <input type="text" class="form-control form-control-lg" id="editThumbnail3" name="edit_thumbnail" style="color: white;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <input type="hidden" id="editProductId">

                            <div class="mb-3">
                                <label for="editTitle" class="form-label fs-6 fw-bold">Product Title:</label>
                                <input type="text" class="form-control form-control-lg" id="editTitle" name="edit_title" style="color: white;">
                            </div>

                            <div class="mb-3">
                                <label for="editDescription" class="form-label fs-6 fw-bold">Description:</label>
                                <textarea class="form-control form-control-lg" id="editDescription" name="edit_description" rows="3" style="color: white;"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-6 fw-bold">Category:</label>
                                <p class="form-control-plaintext fs-5" id="modalProductCategoryStatic" style="color: white;"></p>
                                <input type="hidden" id="editCategoryCidStatic" name="edit_category_cid">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editPrice" class="form-label fs-6 fw-bold">Price:</label>
                                    <input type="number" step="0.01" class="form-control form-control-lg" id="editPrice" name="edit_price" style="color: white;">
                                </div>
                                <div class="col-md-6">
                                    <label for="editDiscount" class="form-label fs-6 fw-bold">Discount:</label>
                                    <input type="number" step="0.01" class="form-control form-control-lg" id="editDiscount" name="edit_discount" style="color: white;">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editStock" class="form-label fs-6 fw-bold">Stock:</label>
                                    <input type="number" class="form-control form-control-lg" id="editStock" name="edit_stock" style="color: white;">
                                </div>
                                <div class="col-md-6">
                                    <label for="editSold" class="form-label fs-6 fw-bold">Sold:</label>
                                    <input type="number" class="form-control form-control-lg" id="editSold" name="edit_sold" style="color: white;">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fs-6 fw-bold">Size:</label>
                                    <input type="text" class="form-control form-control-lg" id="editSize" name="edit_size" placeholder="S, M, L..." style="color: white;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-6 fw-bold">Size2:</label>
                                    <input type="text" class="form-control form-control-lg" id="editSize2" name="edit_size2" placeholder="XL, XXL..." style="color: white;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fs-6 fw-bold">Size3:</label>
                                    <input type="text" class="form-control form-control-lg" id="editSize3" name="edit_size3" placeholder="Free size..." style="color: white;">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fs-6 fw-bold">Color:</label>
                                    <input type="text" class="form-control form-control-lg" id="editColor" name="edit_color" placeholder="Red, Blue..." style="color: white;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-6 fw-bold">Color2:</label>
                                    <input type="text" class="form-control form-control-lg" id="editColor2" name="edit_color2" placeholder="Green, Yellow..." style="color: white;">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="deleteProduct">Delete</button>
                    <button type="button" class="btn btn-info" id="saveProductDetails">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../../template/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="../../template/assets/vendors/chart.js/Chart.min.js"></script>
<script src="../../template/assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="../../template/assets/js/misc.js"></script>
<!-- SCRIPT TÌM SẢN PHẨM THEO TÊN -->
<script>
    document.getElementById('searchProduct').addEventListener('input', function() {
        var filter = this.value.trim().toUpperCase();
        var table = document.querySelector('.table');
        var trs = table.getElementsByTagName('tr');
        // Bắt đầu từ 1 để bỏ qua header
        for (var i = 1; i < trs.length; i++) {
            var td = trs[i].getElementsByTagName('td')[3]; // cột ORDER (thường là cột thứ 3)
            if (td) {
                var txtValue = td.textContent.trim() || td.innerText.trim();
                trs[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    });
</script>
<!-- SCRIPT CHỌN SỐ LƯỢNG SẢN PHẨM TRÊN MỖI TRANG -->
<script>
    function openPrintTab(e) {
        e.preventDefault();
        // Lấy HTML phần bảng
        var printContents = document.querySelector('.table-responsive').outerHTML;
        // Tạo cửa sổ/tab mới
        var printWindow = window.open('', '_blank');
        // Ghi nội dung vào tab mới
        printWindow.document.write(`
        <html>
        <head>
            <title>Print</title>
            <link rel="stylesheet" href="/e-web/admin/template/assets/css/style.css">
            <link rel="stylesheet" href="/e-web/admin/template/assets/vendors/mdi/css/materialdesignicons.min.css">
            <link rel="stylesheet" href="/e-web/admin/template/assets/vendors/css/vendor.bundle.base.css">
            <style>
                body { background: #fff; margin: 40px; }
                .table { width: 100%; }
            </style>
        </head>
        <body>
            ${printContents}
            <script>
                window.onload = function() { window.print(); }
            <\/script>
        </body>
        </html>
    `);
        printWindow.document.close();
    }
</script>
<!-- Thêm nút in vào trang -->
<script>
    function exportTableToExcel(filename) {
        // Lấy dữ liệu từ bảng
        var table = document.querySelector('.table');
        var rows = table.querySelectorAll('tr');
        var data = [];

        // Lấy tiêu đề
        var headers = [];
        rows[0].querySelectorAll('th').forEach(function(th) {
            if (th.textContent.trim() !== '') {  // Bỏ qua cột trống
                headers.push(th.textContent.trim());
            }
        });
        data.push(headers);

        // Lấy dữ liệu từ các hàng
        for (var i = 1; i < rows.length; i++) {
            var row = [];
            var cells = rows[i].querySelectorAll('td');
            
            cells.forEach(function(cell, index) {
                if (index > 1) {  // Bỏ qua 2 cột đầu (checkbox và nút detail)
                    // Xử lý cột hình ảnh
                    if (cell.querySelector('img')) {
                        row.push(cell.textContent.trim()); // Chỉ lấy text bên cạnh hình
                    } else {
                        row.push(cell.textContent.trim());
                    }
                }
            });
            
            if (row.length > 0) {  // Chỉ thêm hàng nếu có dữ liệu
                data.push(row);
            }
        }

        // Tạo workbook mới
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(data);

        // Thiết lập style cho worksheet
        ws['!cols'] = headers.map(function() { return {wch: 15}; }); // Độ rộng cột
        
        // Thêm worksheet vào workbook
        XLSX.utils.book_append_sheet(wb, ws, "Products");

        // Xuất file
        XLSX.writeFile(wb, filename);
    }

    // Thêm hàm xuất PDF nếu cần
    function exportTableToPDF() {
        var {jsPDF} = window.jspdf;
        var doc = new jsPDF('l', 'pt', 'a4'); // landscape orientation
        
        // Tạo styles cho PDF
        var styles = {
            font: 'times',
            fontStyle: 'normal',
            fontSize: 10,
        };
        
        doc.autoTable({
            html: '.table',
            styles: styles,
            margin: {top: 10},
            columnStyles: {
                0: {cellWidth: 30}, // ID
                1: {cellWidth: 100}, // Product
                2: {cellWidth: 80}, // Category
                3: {cellWidth: 60}, // Price
                4: {cellWidth: 40}, // Stock
                5: {cellWidth: 40}, // Size
                6: {cellWidth: 60} // Color
            },
            didDrawCell: function(data) {
                // Xử lý ảnh nếu có
                if (data.cell.raw && data.cell.raw.querySelector('img')) {
                    var img = data.cell.raw.querySelector('img');
                    var dim = data.cell.height - 2;
                    doc.addImage(img.src, 'PNG', data.cell.x + 2, data.cell.y + 2, dim, dim);
                }
            }
        });
        
        doc.save('products.pdf');
    }
</script>
<!-- Thêm script để xử lý modal chi tiết sản phẩm -->
<script>
    $(document).ready(function() {
        // Xử lý sự kiện thay đổi số lượng sản phẩm mỗi trang
        $('#productPerPage').on('change', function() {
            var newLimit = $(this).val();
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('limit', newLimit);
            currentUrl.searchParams.set('page', 1); // Reset về trang 1 khi thay đổi limit
            window.location.href = currentUrl.toString();
        });

        // Xử lý sự kiện thay đổi bộ lọc danh mục (nếu bạn thêm dropdown lọc danh mục)
        // Ví dụ: giả sử dropdown lọc danh mục có ID là 'categoryFilter'
        $('#categoryFilter').on('change', function() {
            var newCategoryId = $(this).val();
            var currentUrl = new URL(window.location.href);
            if (newCategoryId) {
                currentUrl.searchParams.set('category_id', newCategoryId);
            } else {
                currentUrl.searchParams.delete('category_id'); // Xóa param nếu chọn "Tất cả"
            }
            currentUrl.searchParams.set('page', 1); // Reset về trang 1 khi thay đổi bộ lọc
            window.location.href = currentUrl.toString();
        });

        // ... (Thêm các script khác của bạn, ví dụ cho modal chi tiết sản phẩm) ...
        // Xử lý sự kiện khi modal được hiển thị
        $('#productDetailModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Nút kích hoạt modal
            var productId = button.data('product-id'); // Lấy product-id từ data-product-id của nút

            // Xóa dữ liệu cũ trong modal (nếu có)
            $('#modalProductName').text('');
            $('#editProductId').val('');
            $('#modalProductThumbnail').attr('src', '');
            $('#editThumbnail').val('');
            $('#editTitle').val('');
            $('#editDescription').val('');
            $('#modalProductCategoryStatic').text(''); // Clear static category text
            $('#editCategoryCidStatic').val(''); // Clear hidden category ID
            $('#editPrice').val('');
            $('#editStock').val('');
            $('#editSold').val('');
            $('#editSize').val('');
            $('#editSize2').val('');
            $('#editSize3').val('');
            $('#editColor').val('');


            // Tải dữ liệu sản phẩm bằng AJAX
            $.ajax({
                url: '/e-web/admin/pages/produclist/fetch_product_details.php', // Điều chỉnh đường dẫn của bạn
                method: 'GET',
                data: {
                    pid: productId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var product = response.product;
                        // var categories = response.categories; // Không cần thiết nếu category là static

                        // Điền dữ liệu vào form
                        $('#modalProductName').text(product.title);
                        $('#editProductId').val(product.pid);

                        // Xử lý đường dẫn ảnh cho thumbnail1
                        var imgPath = product.thumbnail;
                        if (imgPath.startsWith('admin/assets/images/')) {
                            imgPath = imgPath.substring('admin/assets/images/'.length);
                        }
                        var imgUrl = '/e-web/admin/assets/images/' + encodeURIComponent(imgPath.trim());
                        $('#modalProductThumbnail').attr('src', imgUrl);
                        $('#editThumbnail').val(product.thumbnail);

                        // Xử lý đường dẫn ảnh cho thumbnail2
                        var imgPath2 = product.thumbnail2;
                        if (imgPath2 && imgPath2.startsWith('admin/assets/images/')) {
                            imgPath2 = imgPath2.substring('admin/assets/images/'.length);
                        }
                        var imgUrl2 = imgPath2 ? '/e-web/admin/assets/images/' + encodeURIComponent(imgPath2.trim()) : '';
                        $('#modalProductThumbnail2').attr('src', imgUrl2);
                        $('#editThumbnail2').val(product.thumbnail2 || '');

                        // Xử lý đường dẫn ảnh cho thumbnail3
                        var imgPath3 = product.thumbnail3;
                        if (imgPath3 && imgPath3.startsWith('admin/assets/images/')) {
                            imgPath3 = imgPath3.substring('admin/assets/images/'.length);
                        }
                        var imgUrl3 = imgPath3 ? '/e-web/admin/assets/images/' + encodeURIComponent(imgPath3.trim()) : '';
                        $('#modalProductThumbnail3').attr('src', imgUrl3);
                        $('#editThumbnail3').val(product.thumbnail3 || '');

                        $('#editTitle').val(product.title);
                        $('#editDescription').val(product.description);

                        // Điền category tĩnh
                        $('#modalProductCategoryStatic').text(product.category_name); // Hiển thị tên category
                        $('#editCategoryCidStatic').val(product.cid); // Lưu category ID vào input hidden

                        $('#editPrice').val(product.price);
                        $('#editStock').val(product.stock);
                        $('#editSize').val(product.size);
                        $('#editSize2').val(product.size2 || '');
                        $('#editSize3').val(product.size3 || '');
                        $('#editColor').val(product.color);
                        $('#editDiscount').val(product.discount || ''); // Sử dụng || '' để tránh undefined
                        $('#editSold').val(product.sold || '');
                        $('#editColor2').val(product.color2 || '');

                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    alert('An error occurred while fetching product details.');
                }
            });
        });

        // Xử lý sự kiện khi nút "Save changes" được click
        $('#saveProductDetails').on('click', function() {
            var productId = $('#editProductId').val();
            var newTitle = $('#editTitle').val();
            var newThumbnail = $('#editThumbnail').val();
            var newPrice = $('#editPrice').val();
            var newStock = $('#editStock').val();
            var newSize = $('#editSize').val();
            var newSize2 = $('#editSize2').val();
            var newSize3 = $('#editSize3').val();
            var newColor = $('#editColor').val();
            var newDescription = $('#editDescription').val();
            var categoryCidStatic = $('#editCategoryCidStatic').val(); // Lấy CID từ input hidden
            var newDiscount = $('#editDiscount').val(); // Đảm bảo có input với id="editDiscount"
            var newThumbnail2 = $('#editThumbnail2').val(); // Đảm bảo có input với id="editThumbnail2"
            var newThumbnail3 = $('#editThumbnail3').val(); // Đảm bảo có input với id="editThumbnail3"
            var newRating = $('#editRating').val(); // Đảm bảo có input với id="editRating"
            var newSold = $('#editSold').val(); // Đảm bảo có input với id="editSold"
            var newColor2 = $('#editColor2').val(); // Đảm bảo có input với id="editColor2"
            var formData = {
                pid: productId,
                title: newTitle,
                thumbnail: newThumbnail,
                price: newPrice,
                stock: newStock,
                size: newSize,
                size2: newSize2,
                size3: newSize3,
                color: newColor,
                description: newDescription,
                category_cid: categoryCidStatic, // Gửi category ID tĩnh về server
                discount: newDiscount,
                thumbnail2: newThumbnail2,
                thumbnail3: newThumbnail3,
                rating: newRating,
                sold: newSold,
                color2: newColor2
            };

            $.ajax({
                url: '/e-web/admin/pages/produclist/update_product_details.php', // Điều chỉnh đường dẫn của bạn
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Product updated successfully!');
                        $('#productDetailModal').modal('hide'); // Đóng modal
                        location.reload(); // Tải lại trang để cập nhật bảng
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                    alert('An error occurred while saving changes.');
                }
            });
        });

        // Thêm xử lý sự kiện cho nút Delete
        $('#deleteProduct').on('click', function() {
            if (confirm('Are you sure you want to delete this product?')) {
                var productId = $('#editProductId').val();
                
                $.ajax({
                    url: '/e-web/admin/pages/produclist/delete_product.php',
                    method: 'POST',
                    data: {
                        pid: productId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Product deleted successfully!');
                            $('#productDetailModal').modal('hide');
                            location.reload(); // Tải lại trang để cập nhật danh sách
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert('An error occurred while deleting the product.');
                    }
                });
            }
        });
    });
</script>