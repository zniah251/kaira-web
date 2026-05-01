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
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

// 1. Xử lý tham số phân trang
$allowed_limits = [10, 25, 50, 100];
$limit = 25;
if (isset($_GET['limit']) && is_numeric($_GET['limit']) && in_array(intval($_GET['limit']), $allowed_limits)) {
    $limit = intval($_GET['limit']);
}
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Truy vấn tổng số sản phẩm (không filter)
$total_products_sql = "SELECT COUNT(*) AS total_count FROM category";
$result_total = $conn->query($total_products_sql);
$total_row = $result_total->fetch_assoc();
$total_products = $total_row['total_count'];

// 3. Tính tổng số trang
$total_pages = max(1, ceil($total_products / $limit));

// 4. Đảm bảo trang hiện tại không vượt quá tổng số trang
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

// Lấy dữ liệu category có phân trang
$sql = "
    SELECT
        c1.cid AS id,
        c1.cname AS category_name,
        c1.cslug AS slug,
        c1.cfile AS file,
        c1.parentid,
        COALESCE(c2.cname, 'Gốc') AS parent_category_name,
        c1.is_product_category
    FROM
        category AS c1
    LEFT JOIN
        category AS c2 ON c1.parentid = c2.cid
    ORDER BY
        c1.cid ASC
    LIMIT ?, ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Xử lý cột is_product_category
        $row['is_product_category_display'] = ($row['is_product_category'] == 1) ? 'Yes' : 'No';
        $categories[] = $row;
    }
}

// 3. LẤY DỮ LIỆU CHO DROPDOWN "PARENT CATEGORY" TRONG MODAL
// Lấy tất cả danh mục (chỉ ID và Tên) để điền vào dropdown "Parent category"
$sql_parent_categories = "SELECT cid, cname FROM category ORDER BY cname ASC";
$result_parent_categories = $conn->query($sql_parent_categories);

$parent_categories_options = [];
if ($result_parent_categories->num_rows > 0) {
    while ($row = $result_parent_categories->fetch_assoc()) {
        $parent_categories_options[] = $row;
    }
}
$stmt->close();
$conn->close();
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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- End plugin css for this page -->
    <!-- Layout styles -->

    <link rel="stylesheet" href="/e-web/admin/template/assets/css/style.css">

    <!-- End layout styles -->
    <link rel="shortcut icon" href="../../../admin/template/assets/images/favicon.png" />
    <!-- Sử dụng liên kết CDN mới nhất của Font Awesome -->

    <!-- Thêm vào trước thẻ </body> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.7.0/jspdf.plugin.autotable.min.js"></script>
    <link rel="stylesheet" href="./category-list.css">
    <style>
        
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
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <!-- Search on the left -->
                                        <div class="add-items">
                                            <input type="text" id="searchCategory" class="form-control todo-list-input" placeholder="Search category" style="color:white; width: 300px;">
                                        </div>
                                        <!-- Dropdown and Add button on the right -->
                                        <div class="d-flex align-items-center gap-3">
                                            <select class="btn btn-secondary dropdown-toggle" id="productPerPage" style="text-align: center; text-align-last: center;">
                                                <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                                                <option value="25" <?php if ($limit == 25) echo 'selected'; ?>>25</option>
                                                <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
                                                <option value="100" <?php if ($limit == 100) echo 'selected'; ?>>100</option>
                                            </select>
                                            <a href="" class="btn" data-bs-toggle="modal" data-bs-target="#addCategoryModal" style="background:#6366f1;color:#fff;font-weight:500;box-shadow:0 2px 8px 0 #7b7bff33;">
                                                <i class="mdi mdi-plus"></i> Add Category
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="form-check form-check-muted m-0">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input">
                                                        </label>
                                                    </div>
                                                </th>
                                                <th> ID </th>
                                                <th> CATEGORY NAME </th>
                                                <th> PARENT CATEGORY </th>
                                                <th> IS PRODUCT CATEGORY </th>
                                                <th> ACTIONS </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($categories)): ?>
                                                <?php foreach ($categories as $category): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check form-check-muted m-0">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" class="form-check-input" name="selected_categories[]" value="<?php echo htmlspecialchars($category['id']); ?>">
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td> <?php echo htmlspecialchars($category['id']); ?> </td>
                                                        <td> <?php echo htmlspecialchars($category['category_name']); ?> </td>
                                                        <td>
                                                            <span class="ps-2"><?php echo htmlspecialchars($category['parent_category_name']); ?></span>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            // Điều kiện để đổi màu badge dựa trên giá trị 'Có'/'Không'
                                                            $badge_class = ($category['is_product_category'] == 1) ? 'badge-outline-success' : 'badge-outline-warning';
                                                            ?>
                                                            <div class="badge <?php echo $badge_class; ?>">
                                                                <?php echo htmlspecialchars($category['is_product_category_display']); ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <a href="#" class="btn btn-outline-info btn-sm btn-edit-category" data-id="<?php echo htmlspecialchars($category['id']); ?>">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <a href="delete_category.php?id=<?php echo htmlspecialchars($category['id']); ?>" class="btn btn-outline-secondary btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục &quot;<?php echo htmlspecialchars($category['category_name']); ?>&quot; này không?');">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Không có danh mục nào được tìm thấy.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <nav aria-label="Product list pagination">
                                        <ul class="pagination mb-0">
                                            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>" aria-label="Previous">
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
                                                    <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>">
                                                        <?php echo $i; ?>
                                                    </a>
                                                </li>
                                            <?php
                                            }
                                            ?>

                                            <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>" aria-label="Next">
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
    <!-- Modal for adding new category -->
    <div class="modal category-modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-end modal-fullscreen-md-down custom-right-modal">
            <div class="modal-content category-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm" action="process_add_category.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="categoryTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="categoryTitle" name="cname" placeholder="Enter category title" required>
                        </div>

                        <div class="mb-3">
                            <label for="categorySlug" class="form-label">Slug</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="categorySlug" name="cslug" placeholder="Enter slug">
                                <button class="btn btn-outline-secondary" type="button" id="generateSlugBtn" title="Generate Slug / Settings">
                                    <i class="fa fa-cog"></i> </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="categoryFile" class="form-label">File</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="categoryFile" name="cfile" placeholder="Enter cfile">
                                <button class="btn btn-outline-secondary" type="button" id="generateFileBtn" title="Generate File / Settings">
                                    <i class="fa fa-cog"></i> </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="parentCategory" class="form-label">Parent category</label>
                            <select class="form-select" id="parentCategory" name="parentid">
                                <option value="">Select parent category</option>
                                <option value="0">-- Gốc (No Parent) --</option>
                                <?php foreach ($parent_categories_options as $cat_option): ?>
                                    <option value="<?php echo htmlspecialchars($cat_option['cid']); ?>">
                                        <?php echo htmlspecialchars($cat_option['cname']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="isProductCategory" class="form-label">Is Product Category</label>
                            <select class="form-select" id="isProductCategory" name="is_product_category">
                                <option value="">Select an option</option>
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-info">Add</button>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- End of modal for adding new category -->
    <!-- Modal for editing category -->
    <div class="modal fade category-modal" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-end modal-fullscreen-md-down custom-right-modal">
            <div class="modal-content category-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm" action="process_edit_category.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="editCategoryId" name="cid">

                        <div class="mb-3">
                            <label for="editCategoryTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editCategoryTitle" name="cname" placeholder="Enter category title" required>
                        </div>

                        <div class="mb-3">
                            <label for="editCategorySlug" class="form-label">Slug</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="editCategorySlug" name="cslug" placeholder="Enter slug">
                                <button class="btn btn-outline-secondary" type="button" id="generateEditSlugBtn" title="Generate Slug / Settings">
                                    <i class="fa fa-cog"></i> </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editCategoryFile" class="form-label">File</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="editCategoryFile" name="cfile" placeholder="Enter cfile">
                                <button class="btn btn-outline-secondary" type="button" id="generateEditFileBtn" title="Generate File / Settings">
                                    <i class="fa fa-cog"></i> </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editParentCategory" class="form-label">Parent category</label>
                            <select class="form-select" id="editParentCategory" name="parentid">
                                <option value="">Select parent category</option>
                                <option value="0">-- Gốc (No Parent) --</option>
                                <?php foreach ($parent_categories_options as $cat_option): ?>
                                    <option value="<?php echo htmlspecialchars($cat_option['cid']); ?>">
                                        <?php echo htmlspecialchars($cat_option['cname']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editIsProductCategory" class="form-label">Is Product Category</label>
                            <select class="form-select" id="editIsProductCategory" name="is_product_category">
                                <option value="">Select an option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="submit" class="btn btn-info">Update</button>
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="../../template/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="../../template/assets/vendors/chart.js/Chart.min.js"></script>
<script src="../../template/assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="../../template/assets/js/misc.js"></script>
<script>
    document.getElementById('searchCategory').addEventListener('input', function() {
        var filter = this.value.trim().toUpperCase();
        var table = document.querySelector('.table');
        var trs = table.getElementsByTagName('tr');
        // Bắt đầu từ 1 để bỏ qua header
        for (var i = 1; i < trs.length; i++) {
            var td = trs[i].getElementsByTagName('td')[2]; // cột ORDER (thường là cột thứ 2)
            if (td) {
                var txtValue = td.textContent.trim() || td.innerText.trim();
                trs[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    });
</script>
<script>
    document.getElementById('productPerPage').addEventListener('change', function() {
        const limit = this.value;
        // Lấy lại tham số page hiện tại (nếu có)
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('limit', limit);
        urlParams.set('page', 1); // Reset về trang 1 khi đổi limit
        window.location.search = urlParams.toString();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryTitle = document.getElementById('categoryTitle');
        const categorySlug = document.getElementById('categorySlug');
        const generateSlugBtn = document.getElementById('generateSlugBtn');
        const categoryFile = document.getElementById('categoryFile'); // Input type="text"
        const generateFileBtn = document.getElementById('generateFileBtn'); // Nút cho cfile

        // Hàm chuyển đổi tiêu đề thành slug (giữ nguyên từ trước)
        function generateSlug(title) {
            if (!title) return '';
            title = title.toLowerCase();
            title = title.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            title = title.replace(/đ/g, 'd');
            title = title.replace(/[^a-z0-9\s-]/g, "");
            title = title.replace(/\s+/g, "-");
            title = title.replace(/-+/g, "-");
            title = title.trim();
            return title;
        }

        // Lắng nghe sự kiện input cho Category Title để tự động tạo Slug
        categoryTitle.addEventListener('input', function() {
            // Chỉ tự động tạo slug nếu trường slug rỗng hoặc chưa bị chỉnh sửa thủ công
            if (categorySlug.value === '' || categorySlug.dataset.manualEdit !== 'true') {
                categorySlug.value = generateSlug(this.value);
            }
            // Tự động tạo cfile từ cname nếu cfile rỗng hoặc chưa bị chỉnh sửa thủ công
            if (categoryFile.value === '' || categoryFile.dataset.manualEdit !== 'true') {
                categoryFile.value = generateSlug(this.value) + '.php'; // Thêm .php
            }
        });

        // Lắng nghe sự kiện khi người dùng gõ vào slug để đánh dấu là đã chỉnh sửa thủ công
        categorySlug.addEventListener('input', function() {
            this.dataset.manualEdit = 'true';
        });
        // Lắng nghe sự kiện khi người dùng gõ vào cfile để đánh dấu là đã chỉnh sửa thủ công
        categoryFile.addEventListener('input', function() {
            this.dataset.manualEdit = 'true';
        });

        // Nút Generate Slug (hoặc reset tự động tạo)
        generateSlugBtn.addEventListener('click', function() {
            categorySlug.value = generateSlug(categoryTitle.value);
            categorySlug.dataset.manualEdit = 'false'; // Reset lại trạng thái là không chỉnh sửa thủ công
        });

        // Nút Generate File (tạo cfile từ cname)
        generateFileBtn.addEventListener('click', function() {
            categoryFile.value = generateSlug(categoryTitle.value) + '.php';
            categoryFile.dataset.manualEdit = 'false'; // Reset lại trạng thái là không chỉnh sửa thủ công
        });

        // Reset form khi modal đóng (để lần sau mở lại là form trống)
        const addCategoryModal = document.getElementById('addCategoryModal');
        const addCategoryForm = document.getElementById('addCategoryForm');
        addCategoryModal.addEventListener('hidden.bs.modal', function() {
            addCategoryForm.reset();
            categorySlug.dataset.manualEdit = 'false'; // Reset trạng thái chỉnh sửa slug
            categoryFile.dataset.manualEdit = 'false'; // Reset trạng thái chỉnh sửa cfile
        });
        // --- Logic cho EDIT Category Modal ---
        const editCategoryModal = new bootstrap.Modal(document.getElementById('editCategoryModal')); // Khởi tạo Modal Bootstrap JS
        const editCategoryForm = document.getElementById('editCategoryForm');
        const editCategoryId = document.getElementById('editCategoryId');
        const editCategoryTitle = document.getElementById('editCategoryTitle');
        const editCategorySlug = document.getElementById('editCategorySlug');
        const editCategoryFile = document.getElementById('editCategoryFile');
        const editParentCategory = document.getElementById('editParentCategory');
        const editIsProductCategory = document.getElementById('editIsProductCategory');
        const generateEditSlugBtn = document.getElementById('generateEditSlugBtn');
        const generateEditFileBtn = document.getElementById('generateEditFileBtn');

        // Hàm xử lý khi click nút Edit
        document.querySelectorAll('.btn-edit-category').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ a
                const categoryId = this.dataset.id; // Lấy ID từ data attribute

                // Gửi AJAX request để lấy dữ liệu danh mục
                fetch(`get_category_details.php?id=${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        // Đổ dữ liệu vào form modal Edit
                        editCategoryId.value = data.id;
                        editCategoryTitle.value = data.category_name;
                        editCategorySlug.value = data.slug;
                        editCategoryFile.value = data.file;
                        editParentCategory.value = data.parentid === null ? '0' : data.parentid; // Chọn '0' cho Gốc, hoặc ID
                        editIsProductCategory.value = data.is_product_category;

                        // Reset manualEdit flags cho các trường slug/file
                        editCategorySlug.dataset.manualEdit = 'false';
                        editCategoryFile.dataset.manualEdit = 'false';

                        // Mở modal Edit
                        editCategoryModal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching category details:', error);
                        alert('Không thể tải thông tin danh mục. Vui lòng thử lại.');
                    });
            });
        });

        // Logic tự động tạo slug/file cho modal Edit
        editCategoryTitle.addEventListener('input', function() {
            if (editCategorySlug.value === '' || editCategorySlug.dataset.manualEdit !== 'true') {
                editCategorySlug.value = generateSlug(this.value);
            }
            if (editCategoryFile.value === '' || editCategoryFile.dataset.manualEdit !== 'true') {
                editCategoryFile.value = generateSlug(this.value) + '.php';
            }
        });

        editCategorySlug.addEventListener('input', function() {
            this.dataset.manualEdit = 'true';
        });
        editCategoryFile.addEventListener('input', function() {
            this.dataset.manualEdit = 'true';
        });

        generateEditSlugBtn.addEventListener('click', function() {
            editCategorySlug.value = generateSlug(editCategoryTitle.value);
            editCategorySlug.dataset.manualEdit = 'false';
        });

        generateEditFileBtn.addEventListener('click', function() {
            editCategoryFile.value = generateSlug(editCategoryTitle.value) + '.php';
            editCategoryFile.dataset.manualEdit = 'false';
        });

        // Reset form khi modal Edit đóng
        document.getElementById('editCategoryModal').addEventListener('hidden.bs.modal', function() {
            editCategoryForm.reset();
            editCategorySlug.dataset.manualEdit = 'false';
            editCategoryFile.dataset.manualEdit = 'false';
        });
    });
</script>