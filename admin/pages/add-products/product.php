<!DOCTYPE html>
<html lang="en">
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
// Đặt đoạn code PHP lấy danh mục sản phẩm TẠI ĐÂY
// ====================================================================
$sql_categories = "SELECT cid, cname FROM category WHERE is_product_category = 1 ORDER BY cname ASC";
$result_categories = $conn->query($sql_categories);

$categories = []; // Khởi tạo mảng $categories
if ($result_categories) { // Luôn kiểm tra $result_categories có hợp lệ không
    if ($result_categories->num_rows > 0) {
        while ($row_cat = $result_categories->fetch_assoc()) {
            $categories[] = $row_cat;
        }
    }
} else {
    // Xử lý lỗi truy vấn nếu có
    error_log("Lỗi truy vấn danh mục: " . $conn->error);
    // Bạn có thể hiển thị thông báo lỗi cho người dùng hoặc log lại
    // echo "Có lỗi khi tải danh mục sản phẩm. Vui lòng thử lại sau.";
}
// ====================================================================
// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish_product'])) {
    // Lấy dữ liệu từ form
    $title = $_POST['title'] ?? '';
    $pid = $_POST['pid'] ?? '';
    $cid = $_POST['cid'] ?? ''; // Giá trị cid từ dropdown
    $description = $_POST['description'] ?? '';
    $description = preg_replace('/<\/?div[^>]*>/', '', $description);
    $description = preg_replace('/<br\s*\/?>/i', '', $description);
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    $sold = isset($_POST['sold']) ? intval($_POST['sold']) : 0;
    $stock = $_POST['stock'] ?? '';
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 5.0;
    
    // Khởi tạo các biến
    $size = $size2 = $size3 = '';
    $color = $color2 = '';
    $sold = 0; // Khởi tạo giá trị mặc định cho sold

    // Duyệt qua các option động
    if (!empty($_POST['option_type']) && !empty($_POST['option_value'])) {
        $sizeCount = 0;
        $colorCount = 0;
        foreach ($_POST['option_type'] as $idx => $type) {
            $value = trim($_POST['option_value'][$idx]);
            if (strtolower($type) === 'size' && $value !== '') {
                $sizeCount++;
                if ($sizeCount == 1) $size = $value;
                elseif ($sizeCount == 2) $size2 = $value;
                elseif ($sizeCount == 3) $size3 = $value;
            }
            if (strtolower($type) === 'color' && $value !== '') {
                $colorCount++;
                if ($colorCount == 1) $color = $value;
                elseif ($colorCount == 2) $color2 = $value;
            }
            if (strtolower($type) === 'sold' && $value !== '') {
                $sold = intval($value); // Chuyển đổi giá trị sold thành số nguyên
            }
        }
    }
    // Xử lý upload ảnh
    // Xử lý upload nhiều ảnh (thumbnail, thumbnail2, thumbnail3)
    $thumbnail = '';
    $thumbnail2 = '';
    $thumbnail3 = '';
    $uploadedFilesArray = []; // Mảng để lưu tên file đã upload

    // Kiểm tra xem có file nào được upload với name="img[]" không
    if (isset($_FILES['img']) && is_array($_FILES['img']['name'])) {
        $fileCount = count($_FILES['img']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            // Kiểm tra xem file có lỗi không và có được upload không
            if ($_FILES['img']['error'][$i] === 0) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/e-web/admin/assets/images/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = uniqid() . '_' . basename($_FILES['img']['name'][$i]);
                $targetFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['img']['tmp_name'][$i], $targetFile)) {
                    $uploadedFilesArray[] = $fileName; // Thêm tên file vào mảng
                } else {
                    echo "Upload ảnh thứ " . ($i + 1) . " thất bại.<br>";
                    $uploadedFilesArray[] = ''; // Đặt rỗng nếu upload lỗi
                }
            } else {
                $uploadedFilesArray[] = ''; // Đặt rỗng nếu không có file hoặc có lỗi
            }
        }
    }
    // Gán các giá trị từ mảng uploadedFilesArray vào các biến thumbnail
    $thumbnail = isset($uploadedFilesArray[0]) ? $uploadedFilesArray[0] : '';
    $thumbnail2 = isset($uploadedFilesArray[1]) ? $uploadedFilesArray[1] : '';
    $thumbnail3 = isset($uploadedFilesArray[2]) ? $uploadedFilesArray[2] : '';
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 5.0;

    // Khởi tạo rỗng size & color
    $size = $size2 = $size3 = '';
    $color = $color2 = '';

    // Duyệt qua các option động
    if (!empty($_POST['option_type']) && !empty($_POST['option_value'])) {
        $sizeCount = 0;
        $colorCount = 0;
        $soldCount = 0;
        foreach ($_POST['option_type'] as $idx => $type) {
            $value = trim($_POST['option_value'][$idx]);
            if (strtolower($type) === 'size' && $value !== '') {
                $sizeCount++;
                if ($sizeCount == 1) $size = $value;
                elseif ($sizeCount == 2) $size2 = $value;
                elseif ($sizeCount == 3) $size3 = $value;
            }
            if (strtolower($type) === 'color' && $value !== '') {
                $colorCount++;
                if ($colorCount == 1) $color = $value;
                elseif ($colorCount == 2) $color2 = $value;
            }
            if (strtolower($type) === 'sold' && $value !== '') {
                $soldCount++;
                if ($soldCount == 1) $sold = intval($value); // Chuyển đổi sang số nguyên
            }
        }
    }
    // Chuẩn bị và thực thi truy vấn
    $stmt = $conn->prepare("INSERT INTO product (pid, cid, title, price, discount, thumbnail,thumbnail2,thumbnail3,description, stock, size, size2,size3,rating, sold, color,color2)VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "iisddssssssssisss",
        $pid,
        $cid,
        $title,
        $price,
        $discount,
        $thumbnail,
        $thumbnail2,
        $thumbnail3,
        $description,
        $stock,
        $size,
        $size2,
        $size3,
        $rating,
        $sold,
        $color,
        $color2
    );

    //$stmt->close();
}
?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Corona Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../../admin/template/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../../admin/template/assets/vendors/css/vendor.bundle.base.css">

    <!-- Layout styles -->
    <link rel="stylesheet" href="./addproduct.css">
    <link rel="stylesheet" href="/e-web/admin/template/assets/css/style.css">
    <link rel="stylesheet" href="/e-web/admin/template/assets/css/switch.css">

    <link rel="shortcut icon" href="../../../admin/template/assets/images/favicon.png" />
    <link rel="stylesheet" href="../../../admin/template/assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="../../../admin/template/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">

    <!-- Sử dụng liên kết CDN mới nhất của Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Times New Roman', serif;
            /* Thêm fallback font */
        }
        .custom-select-box {
            width: 100%;
            padding: 4px 12px;
            background-color: #111;
            color: #fff;
            border: 1px solid #555;
            border-radius: 4px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .custom-select-box option {
            background-color: #111;
            color: #fff;
        }

        #inventoryTabs .btn {
            color: #ffffff;
            box-shadow: none;
            border: none;
            transition: 0.2s;
        }

        #inventoryTabs .btn.active {
            color: #fff;
            border: 1.5px;
            font-weight: 600;
        }

        #inventoryTabs .btn:focus {
            box-shadow: none;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <?php include('../../template/sidebar.php'); ?>
        <div class="container-fluid page-body-wrapper">
            <?php include('../../template/navbar.php'); ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['publish_product'])) {
                        if ($stmt->execute()) {
                            echo '<div class="alert alert-success">Product published successfully!</div>';
                        } else {
                            echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
                        }
                        $stmt->close(); // Đóng sau khi đã execute và xử lý xong
                    }
                    ?>
                    <form class="forms-sample" method="POST" enctype="multipart/form-data" id="addProductForm">
                        <div class="page-header">
                            <h4 class="page-title" style="margin-bottom: 0;">Add a product</h4>
                            <div class="d-flex justify-content-between align-items-center mb-3" style="gap: 10px;">
                                <div class="action-buttons" style="display: flex; gap: 10px;">
                                    <button type="button" class="btn btn-outline-secondary btn-icon-text">Discard <i class="mdi mdi-delete-forever btn-icon-append"></i></button>
                                    <button type="button" class="btn btn-outline-secondary btn-icon-text">Save draft <i class="mdi mdi-file-check btn-icon-append"></i></button>
                                    <button type="submit" class="btn btn-outline-info btn-icon-text" name="publish_product">Public product <i class="mdi mdi-printer btn-icon-append"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h4 class="card-title">Product information</h4>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Product Title</label>
                                            <input type="text" class="form-control" id="exampleInputName1" name="title"
                                                placeholder="Write title here..." required>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="productIdInput">Product id</label>
                                                    <input type="text" class="form-control" id="productIdInput" name="pid" placeholder="pid">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="categoryIdInput">Category id</label>
                                                    <input type="text" class="form-control" id="categoryIdInput" name="cid" placeholder="cid">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Product Description</label>
                                            <div class="btn-group d-block mt2" role="group" aria-label="Basic example">
                                                <button type="button" class="btn" id="boldBtn"><i class="mdi mdi-format-bold"></i></button>
                                                <button type="button" class="btn" id="italicBtn"><i class="mdi mdi-format-italic"></i></button>
                                                <button type="button" class="btn" id="underlineBtn"><i class="mdi mdi-format-underline"></i></button>
                                                <button type="button" class="btn" id="ulBtn"><i class="mdi mdi-format-list-bulleted"></i></button>
                                                <button type="button" class="btn" id="olBtn"><i class="mdi mdi-format-list-numbered"></i></button>
                                            </div>
                                            <div id="editor" contenteditable="true" class="form-control" style="min-height:120px;"></div>
                                            <input type="hidden" name="description" id="productDescriptionHiddenInput">
                                        </div>
                                        <!-- Thêm enctype và method vào form cha -->
                                        <script>
                                            // Đảm bảo form cha có thuộc tính enctype và method
                                            document.addEventListener('DOMContentLoaded', function() {
                                                var form = document.querySelector('form.forms-sample');
                                                if (form) {
                                                    form.setAttribute('enctype', 'multipart/form-data');
                                                    form.setAttribute('method', 'post');
                                                }
                                                // Lấy các phần tử cần thiết
                                                const editor = document.getElementById('editor');
                                                const productDescriptionHiddenInput = document.getElementById('productDescriptionHiddenInput');
                                                // ...các biến nút khác...

                                                // Hàm chung để thực hiện lệnh định dạng
                                                function executeCommand(command, value = null) {
                                                    editor.focus();
                                                    document.execCommand(command, false, value);
                                                    updateHiddenInput();
                                                }

                                                // Cập nhật giá trị của input ẩn với nội dung HTML của editor
                                                function updateHiddenInput() {
                                                    productDescriptionHiddenInput.value = editor.innerHTML;
                                                }

                                                // Gán sự kiện click cho các nút
                                                boldBtn.addEventListener('click', () => executeCommand('bold'));
                                                italicBtn.addEventListener('click', () => executeCommand('italic'));
                                                underlineBtn.addEventListener('click', () => executeCommand('underline'));
                                                ulBtn.addEventListener('click', () => executeCommand('insertUnorderedList'));
                                                olBtn.addEventListener('click', () => executeCommand('insertOrderedList'));
                                                // ...nút khác nếu có...

                                                // Đặt đoạn này ở đây:
                                                editor.addEventListener('input', updateHiddenInput);
                                                updateHiddenInput();
                                            });
                                        </script>

                                        <div class="form-group">
                                            <label>File upload</label>
                                            <input type="file" name="img[]" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="file" name="img[]" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="file" name="img[]" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Variants -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h4 class="card-title">Variants</h4>
                                        <div class="form-group">
                                            <label for="exampleSelectCategory">Options</label>
                                            <div id="optionsContainer">
                                                <div class="row option-row mb-3">
                                                    <div class="col-md-4">
                                                        <select class="form-control" name="option_type[]">
                                                            <option>Size</option>
                                                            <option>Color</option>
                                                            <option>Rating</option>
                                                            <option>Sold</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-7"> <input type="text" class="form-control" name="option_value[]" placeholder="Enter size"> </div>
                                                    <div class="col-md-1 d-flex align-items-center"> <button type="button" class="btn btn-outline-info btn-sm remove-option-btn" style="width: 100%;"><i class="mdi mdi-delete"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-info btn-fw" id="addOptionBtn">+ Add another option</button>
                                        </div>
                                    </div>

                                </div>
                                <!-- Inventory -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h4 class="card-title">Inventory</h4>
                                        <div class="row">
                                            <!-- Sidebar -->
                                            <div class="col-md-4 border-end" style="padding-right: 20px;">
                                                <div class="nav flex-column nav-pills" id="inventoryTabs">
                                                    <button class="btn btn-outline-info btn-fw mb-2 text-start active" type="button" data-tab="stock">
                                                        <i class="fa fa-cube me-2"></i> Restock
                                                    </button>
                                                    <button class="btn btn-outline-info btn-fw mb-2 text-start" type="button" data-tab="shipping">
                                                        <i class="fa fa-truck me-2"></i> Shipping
                                                    </button>
                                                    <button class="btn btn-outline-info btn-fw mb-2 text-start" type="button" data-tab="global">
                                                        <i class="fa fa-globe me-2"></i> Global Delivery
                                                    </button>
                                                    <button class="btn btn-outline-info btn-fw mb-2 text-start" type="button" data-tab="attributes">
                                                        <i class="fa fa-link me-2"></i> Attributes
                                                    </button>
                                                    <button class="btn btn-outline-info btn-fw text-start" type="button" data-tab="advanced">
                                                        <i class="fa fa-lock me-2"></i> Advanced
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Content -->
                                            <div class="col-md-8" style="padding-left: 24px;">
                                                <div id="tab-restock" class="tab-content-inventory" style="display:block;">
                                                    <!-- Nội dung Restock -->
                                                    <h6>Options</h6>
                                                    <label class="me-2 mb-0">Add to Stock</label>
                                                    <div class="d-flex align-items-center mb-3" style="gap: 10px; max-width: 350px;">
                                                        <input type="number" name="stock" class="form-control" placeholder="Quantity" style="max-width: 200px; ">
                                                        <button class="btn btn-outline-info btn-md">Confirm</button>
                                                    </div>

                                                </div>
                                                <div id="tab-shipping" class="tab-content-inventory" style="display:none;">
                                                    <!-- Nội dung Shipping -->
                                                    <h6>Shipping</h6>
                                                    <p>Shipping options and info...</p>
                                                </div>
                                                <div id="tab-global" class="tab-content-inventory" style="display:none;">
                                                    <!-- Nội dung Global Delivery -->
                                                    <h6>Global Delivery</h6>
                                                    <p>Global delivery options...</p>
                                                </div>
                                                <div id="tab-attributes" class="tab-content-inventory" style="display:none;">
                                                    <!-- Nội dung Attributes -->
                                                    <h6>Attributes</h6>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="fragile">
                                                        <label class="form-check-label" for="fragile">Fragile Product</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="biodegradable">
                                                        <label class="form-check-label" for="biodegradable">Biodegradable</label>
                                                    </div>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="frozen" checked>
                                                        <label class="form-check-label" for="frozen">Frozen Product</label>
                                                        <input type="text" class="form-control mt-2" placeholder="Max. allowed Temperature" style="max-width: 300px;">
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="expiry" checked>
                                                        <label class="form-check-label" for="expiry">Expiry Date of Product</label>
                                                        <input type="date" class="form-control mt-2" value="2025-05-30" style="max-width: 300px;">
                                                    </div>
                                                </div>
                                                <div id="tab-advanced" class="tab-content-inventory" style="display:none;">
                                                    <!-- Nội dung Advanced -->
                                                    <h6>Advanced</h6>
                                                    <div class="form-row d-flex align-items-center" style="gap: 16px;">
                                                        <div class="form-group mb-0" style="flex: 1;">
                                                            <label for="productIdType">Product ID Type</label>
                                                            <input type="text" class="form-control" id="productIdType" name="product_id_type" placeholder="cid">
                                                        </div>
                                                        <div class="form-group mb-0" style="flex: 1;">
                                                            <label for="productId">Product ID</label>
                                                            <input type="text" class="form-control" id="productId" name="product_id" placeholder="pid">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.querySelectorAll('#inventoryTabs button').forEach(btn => {
                                                btn.addEventListener('click', function() {
                                                    // Remove active from all buttons
                                                    document.querySelectorAll('#inventoryTabs button').forEach(b => b.classList.remove('active'));
                                                    this.classList.add('active');
                                                    // Hide all tab contents
                                                    document.querySelectorAll('.tab-content-inventory').forEach(tab => tab.style.display = 'none');
                                                    // Show selected tab
                                                    const tabId = 'tab-' + this.getAttribute('data-tab');
                                                    document.getElementById(tabId).style.display = 'block';
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <!-- Cột phải: Pricing, Organize, ... -->
                            <div class="col-lg-4">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h4 class="card-title">Pricing</h4>

                                        <div class="form-group">
                                            <label for="price">Base price</label>
                                            <input type="text" class="form-control" id="price" name="price"
                                                placeholder="Price">
                                        </div>
                                        <div class="form-group">
                                            <label for="discount">Discount price</label>
                                            <input type="text" class="form-control" id="discount" name="discount"
                                                placeholder="Discounted Price">
                                        </div>

                                    </div>
                                </div>
                                <div class="card col-mt-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Organize</h4>
                                            <div class="form-group">
                                                <label for="exampleSelectCategory">Category</label>
                                                <select class="form-control" id="exampleSelectCategory">
                                                    <option id="7">Shop for men</option>
                                                    <option id="8">Shop for women</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="cid">Collection</label>
                                                <select class="form-control" id="cid" name="cid" required>
                                                    <option value="">-- Select Collection --</option>
                                                    <?php
                                                    foreach ($categories as $category) {
                                                        echo '<option value="' . htmlspecialchars($category['cid']) . '">' . htmlspecialchars($category['cname']) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Có thể thêm các card khác bên dưới nếu muốn -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            bootstrapdash.com 2021</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a
                                href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap
                                admin template</a> from Bootstrapdash.com</span>
                    </div>
                </footer>

                <!-- partial -->

            </div>
        </div>

    </div>
    <script src="../../template/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../../template/assets/vendors/chart.js/Chart.min.js"></script>
    <script src="../../template/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../../template/assets/js/misc.js"></script>
    <script src="/e-web/admin/template/assets/js/select2.js"></script>
    <script src="/e-web/admin/template/assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <!-- Custom js for this page -->
    <script src="../../template/assets/js/file-upload.js"></script>
    <script src="../../template/assets/js/typeahead.js"></script>

    <!-- End custom js for this page -->
    <script>
        function addOption() {
            const container = document.getElementById('variant-container');

            const group = document.createElement('div');
            group.className = 'variant-group';
            group.innerHTML = `
            <label>Options <a href="#" class="remove-option" onclick="removeOption(this)">Remove</a></label>
            <select class="form-select">
              <option value="size">Size</option>
              <option value="color">Color</option>
              <option value="Sold">Sold</option>
            </select>
            <textarea placeholder="Enter values..." style="width: 100%; padding: 10px; color: black;"></textarea>
          `;

            container.appendChild(group);
        }

        function removeOption(link) {
            const group = link.closest('.variant-group');
            group.remove();
        }
    </script>
    <script>
        // Lấy các phần tử cần thiết
        const editor = document.getElementById('editor');
        const productDescriptionHiddenInput = document.getElementById('productDescriptionHiddenInput');
        const boldBtn = document.getElementById('boldBtn');
        const italicBtn = document.getElementById('italicBtn');
        const underlineBtn = document.getElementById('underlineBtn');
        const ulBtn = document.getElementById('ulBtn');
        const olBtn = document.getElementById('olBtn');
        // Hàm chung để thực hiện lệnh định dạng
        function executeCommand(command, value = null) {
            // Đảm bảo editor đang được focus để lệnh hoạt động
            editor.focus();
            document.execCommand(command, false, value);
            updateHiddenInput(); // Cập nhật input ẩn sau mỗi thay đổi
        }

        // Cập nhật giá trị của input ẩn với nội dung HTML của editor
        function updateHiddenInput() {
            productDescriptionHiddenInput.value = editor.innerHTML;
        }

        // Gán sự kiện click cho các nút
        boldBtn.addEventListener('click', () => executeCommand('bold'));
        italicBtn.addEventListener('click', () => executeCommand('italic'));
        underlineBtn.addEventListener('click', () => executeCommand('underline'));
        ulBtn.addEventListener('click', () => executeCommand('insertUnorderedList'));
        olBtn.addEventListener('click', () => executeCommand('insertOrderedList'));
        linkBtn.addEventListener('click', () => {
            const url = prompt('Nhập URL:', 'http://');
            if (url) {
                executeCommand('createLink', url);
            }
        });

        // Cập nhật input ẩn khi nội dung của editor thay đổi (ví dụ: khi người dùng gõ trực tiếp)
        editor.addEventListener('input', updateHiddenInput);

        // Khởi tạo giá trị cho hidden input khi tải trang (nếu có nội dung ban đầu)
        // Nếu bạn muốn tải nội dung từ server vào editor, bạn sẽ đặt nó vào editor.innerHTML
        // Ví dụ: editor.innerHTML = "<b>Sản phẩm</b> mới!";
        updateHiddenInput(); // Cập nhật lần đầu
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addOptionBtn = document.getElementById('addOptionBtn');
            const optionsContainer = document.getElementById('optionsContainer');

            // Hàm để thêm một dòng option mới
            function addOptionRow() {
                // Tạo chuỗi HTML cho một dòng option mới
                const newOptionRowHTML = `
                <div class="row option-row mb-3">
                    <div class="col-md-4">
                        <select class="form-control" name="option_type[]">
                            <option>Size</option>
                            <option>Color</option>
                            <option>Sold</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control" name="option_value[]" placeholder="Enter value">
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-outline-info btn-sm remove-option-btn" style="width: 100%;"><i class="mdi mdi-delete"></i></button>
                    </div>
                </div>
            `;

                // Chèn chuỗi HTML vào cuối optionsContainer
                optionsContainer.insertAdjacentHTML('beforeend', newOptionRowHTML);

                // Gán lại sự kiện cho các nút xóa (vì các nút mới được tạo)
                attachRemoveOptionEventListeners();
            }

            // Hàm để gán sự kiện click cho tất cả các nút xóa
            function attachRemoveOptionEventListeners() {
                // Lấy tất cả các nút có class 'remove-option-btn'
                const removeButtons = document.querySelectorAll('.remove-option-btn');

                // Loại bỏ tất cả các event listener cũ để tránh trùng lặp
                removeButtons.forEach(button => {
                    // Đây là một cách đơn giản để tránh thêm listener nhiều lần.
                    // Một cách tốt hơn là sử dụng event delegation nếu bạn có nhiều phần tử động.
                    button.removeEventListener('click', handleRemoveOption); // Remove previous listener
                    button.addEventListener('click', handleRemoveOption); // Add new listener
                });
            }

            // Hàm xử lý khi nút xóa được click
            function handleRemoveOption(event) {
                // Lấy phần tử cha gần nhất có class 'option-row' (là toàn bộ dòng option)
                const rowToRemove = event.target.closest('.option-row');
                if (rowToRemove) {
                    rowToRemove.remove(); // Xóa dòng đó khỏi DOM
                }
            }

            // Gán sự kiện click cho nút "Add another option"
            addOptionBtn.addEventListener('click', addOptionRow);

            // Gán sự kiện cho các nút xóa ban đầu khi trang tải
            attachRemoveOptionEventListeners();

            // Cập nhật placeholder khi select box thay đổi
            optionsContainer.addEventListener('change', function(event) {
                if (event.target.tagName === 'SELECT' && event.target.name === 'option_type[]') {
                    const selectedOption = event.target.value;
                    const inputElement = event.target.closest('.option-row').querySelector('input[name="option_value[]"]');
                    if (inputElement) {
                        inputElement.placeholder = `Enter ${selectedOption.toLowerCase()}`;
                    }
                }
            });
        });
    </script>
    <!---->

</body>

</html>