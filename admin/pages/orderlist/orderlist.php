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

// Xử lý xóa đơn hàng
if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];
    
    // Xóa các bản ghi liên quan trong bảng order_detail trước
    $delete_details_sql = "DELETE FROM order_detail WHERE oid = ?";
    $stmt_details = $conn->prepare($delete_details_sql);
    $stmt_details->bind_param("s", $order_id);
    $stmt_details->execute();
    $stmt_details->close();
    
    // Sau đó xóa đơn hàng từ bảng orders
    $delete_order_sql = "DELETE FROM orders WHERE oid = ?";
    $stmt_order = $conn->prepare($delete_order_sql);
    $stmt_order->bind_param("s", $order_id);
    
    if ($stmt_order->execute()) {
        echo "<script>alert('Order deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting order!');</script>";
    }
    $stmt_order->close();
}

// 1. Xử lý tham số phân trang
$allowed_limits = [10, 25, 50, 100];
$limit = 25;
if (isset($_GET['limit']) && is_numeric($_GET['limit']) && in_array(intval($_GET['limit']), $allowed_limits)) {
    $limit = intval($_GET['limit']);
}
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Truy vấn tổng số sản phẩm (không filter)
$total_products_sql = "SELECT COUNT(*) AS total_count FROM orders";
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
// BƯỚC 3: TRUY VẤN DỮ LIỆU ĐƠN HÀNG VÀ KẾT NỐI VỚI BẢNG USERS/CUSTOMERS
$sql = "SELECT 
            o.oid, 
            o.uid, 
            o.totalfinal, 
            o.vid, 
            o.destatus, 
            o.paymethod, 
            o.paystatus, 
            o.paytime, 
            o.create_at,
            u.uname AS customer_name -- Lấy tên khách hàng từ bảng users
        FROM 
            orders AS o
        JOIN 
            users AS u ON o.uid = u.uid -- Giả định bảng users có cột 'id' là uid
        ORDER BY 
            o.create_at DESC 
        LIMIT ? OFFSET ?";
// Nếu có điều kiện lọc, thêm vào truy vấn chính
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset); // Chú ý: "ii" cho hai số nguyên
$stmt->execute();
$result = $stmt->get_result();
// ... xử lý $result ...
$stmt->close();

$filter_cid = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
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
    <style>
        body {
            font-family: 'Times New Roman', serif;
            /* Thêm fallback font */
        }

        /* Đặt sau Bootstrap, hoặc trong file riêng cuối trang */
        .btn-link.custom-purple {
            color: #7c3aed !important;
            /* Màu tím */
            background: none !important;
            box-shadow: none !important;
            border: none !important;
            text-decoration: none !important;
        }

        .btn-link.custom-gray {
            color: #9ca3af !important;
            /* Màu xám */
            background: none !important;
            box-shadow: none !important;
            border: none !important;
            text-decoration: none !important;
        }

        /* Đảm bảo form không bị block */
        .d-flex form {
            display: inline-block;
            margin: 0;
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
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <?php
                                                // Đếm số đơn hàng đang chờ thanh toán
                                                $pending_payment_sql = "SELECT COUNT(*) as pending_count 
                                                                      FROM orders 
                                                                      WHERE paystatus = 'Pending'";
                                                $pending_result = $conn->query($pending_payment_sql);
                                                $pending_row = $pending_result->fetch_assoc();
                                                ?>
                                                <h3 class="mb-0"><?php echo $pending_row['pending_count']; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="icon icon-box-success ">
                                                <span class="mdi mdi-calendar-clock icon-item"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Pending Payment</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <?php
                                                // Đếm số đơn hàng đã hoàn thành (delivered và paid)
                                                $completed_orders_sql = "SELECT COUNT(*) as completed_count 
                                                                      FROM orders 
                                                                      WHERE destatus = 'Delivered' 
                                                                      AND paystatus = 'Paid'";
                                                $completed_result = $conn->query($completed_orders_sql);
                                                $completed_row = $completed_result->fetch_assoc();
                                                ?>
                                                <h3 class="mb-0"><?php echo $completed_row['completed_count']; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="icon icon-box-success">
                                                <span class="mdi mdi-check-all icon-item"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Completed Orders</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <?php
                                                // Đếm số đơn hàng đã hoàn tiền
                                                $refunded_orders_sql = "SELECT COUNT(*) as refunded_count 
                                                                      FROM orders 
                                                                      WHERE paystatus = 'Refunded'";
                                                $refunded_result = $conn->query($refunded_orders_sql);
                                                $refunded_row = $refunded_result->fetch_assoc();
                                                ?>
                                                <h3 class="mb-0"><?php echo $refunded_row['refunded_count']; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="icon icon-box-danger">
                                                <span class="mdi mdi-credit-card icon-item"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Refunded</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <?php
                                                // Đếm số đơn hàng đã hủy
                                                $cancelled_orders_sql = "SELECT COUNT(*) as cancelled_count 
                                                                      FROM orders 
                                                                      WHERE destatus = 'Cancelled'";
                                                $cancelled_result = $conn->query($cancelled_orders_sql);
                                                $cancelled_row = $cancelled_result->fetch_assoc();
                                                ?>
                                                <h3 class="mb-0"><?php echo $cancelled_row['cancelled_count']; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="icon icon-box-danger">
                                                <span class="mdi mdi-alert icon-item"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Cancelled Orders</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center mb-4">
                                        <div class="col">
                                            <input type="text" id="searchOrder" class="form-control todo-list-input" placeholder="Search order" style="color:white;">
                                        </div>
                                        <div class="col">
                                            <select class="form-control" id="orderStatusFilter" name="status" style="color: #fff;">
                                                <option value="">All Status</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Confirmed">Confirmed</option>
                                                <option value="Shipping">Shipping</option>
                                                <option value="Delivered">Delivered</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <select class="form-control" id="paymentStatusFilter" name="payment_status" style="color: #fff;">
                                                <option value="">All Payment Status</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Awaiting refund">Awaiting refund</option>
                                                <option value="Refunded">Refunded</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <!-- Select số lượng sản phẩm -->
                                            <select class="btn btn-secondary dropdown-toggle" id="productPerPage" style="text-align: center; text-align-last: center;">
                                                <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                                                <option value="25" <?php if ($limit == 25) echo 'selected'; ?>>25</option>
                                                <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
                                                <option value="100" <?php if ($limit == 100) echo 'selected'; ?>>100</option>
                                            </select>
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
                                                    <th> ORDER ID</th>
                                                    <th> CUSTOMERS </th>
                                                    <th> TOTAL </th>
                                                    <th> VOUCHER ID </th>
                                                    <th> ORDER STATUS </th>
                                                    <th> PAYMENT STATUS </th>
                                                    <th> PAYMENT METHOD </th>
                                                    <th> CREATED AT </th>
                                                    <th> ACTIONS </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($result->num_rows > 0) {
                                                    // BƯỚC 4: LẶP QUA DỮ LIỆU VÀ HIỂN THỊ
                                                    while ($order = $result->fetch_assoc()) {
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <div class="form-check form-check-muted m-0">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" class="form-check-input">
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td> <?php echo htmlspecialchars($order['oid']); ?> </td>
                                                            <td>
                                                                <span class="ps-2"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                                                            </td>
                                                            <td> <?php echo number_format($order['totalfinal'], 2); ?> </td>
                                                            <td> <?php echo htmlspecialchars($order['vid'] ?? 'N/A'); ?> </td>
                                                            <td>
                                                                <?php
                                                                $order_status_class = '';
                                                                switch ($order['destatus']) {
                                                                    case 'Pending':
                                                                        $order_status_class = 'badge-outline-warning';
                                                                        break;
                                                                    case 'Confirmed':
                                                                        $order_status_class = 'badge-outline-info';
                                                                        break;
                                                                    case 'Shipping':
                                                                        $order_status_class = 'badge-outline-primary';
                                                                        break;
                                                                    case 'Delivered':
                                                                        $order_status_class = 'badge-outline-success';
                                                                        break;
                                                                    case 'Cancelled':
                                                                        $order_status_class = 'badge-outline-danger';
                                                                        break;
                                                                    default:
                                                                        $order_status_class = 'badge-outline-secondary';
                                                                        break;
                                                                }
                                                                ?>
                                                                <div class="badge <?php echo $order_status_class; ?>"><?php echo htmlspecialchars($order['destatus']); ?></div>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $payment_status_class = '';
                                                                switch ($order['paystatus']) {
                                                                    case 'Pending':
                                                                        $payment_status_class = 'badge-outline-warning';
                                                                        break;
                                                                    case 'Paid':
                                                                        $payment_status_class = 'badge-outline-success';
                                                                        break;
                                                                    case 'Awaiting refund':
                                                                        $payment_status_class = 'badge-outline-info';
                                                                        break;
                                                                    case 'Refunded':
                                                                        $payment_status_class = 'badge-outline-secondary';
                                                                        break;
                                                                    default:
                                                                        $payment_status_class = 'badge-outline-secondary';
                                                                        break;
                                                                }
                                                                ?>
                                                                <div class="badge <?php echo $payment_status_class; ?>"><?php echo htmlspecialchars($order['paystatus']); ?></div>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $paymethod = $order['paymethod'];
                                                                switch (strtoupper($paymethod)) {
                                                                    case 'WALLET':
                                                                    case 'E-WALLET':
                                                                        echo 'E-wallet';
                                                                        break;
                                                                    case 'CREDIT CARD':
                                                                        echo 'Credit Card';
                                                                        break;
                                                                    case 'BANK':
                                                                        echo 'Bank';
                                                                        break;
                                                                    case 'COD':
                                                                        echo 'COD';
                                                                        break;
                                                                    default:
                                                                        echo htmlspecialchars($paymethod);
                                                                }
                                                                ?>
                                                            </td>
                                                            <td> <?php echo date('d M Y', strtotime($order['create_at'])); ?> </td>
                                                            <td>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <a href="/e-web/admin/pages/orderdetails/orderdetails.php?oid=<?php echo htmlspecialchars($order['oid']); ?>"
                                                                        class="btn btn-link custom-purple p-0">
                                                                        <i class="mdi mdi-plus-circle"></i>
                                                                    </a>
                                                                    <form method="POST" onsubmit="return confirmDelete()">
                                                                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['oid']); ?>">
                                                                        <button type="submit" name="delete_order" class="btn btn-link custom-gray p-0">
                                                                            <i class="mdi mdi-close-circle"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="10" class="text-center">No orders found.</td></tr>';
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
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.querySelector('.table');
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            var downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
    <script>
        function exportTableToPDF() {
            var {
                jsPDF
            } = window.jspdf;
            var doc = new jsPDF();
            doc.autoTable({
                html: '.table'
            });
            doc.save('orders.pdf');
        }
    </script>
    <!-- filter order status -->
    <script>
        document.getElementById('orderStatusFilter').addEventListener('change', function() {
            filterOrders();
        });

        document.getElementById('paymentStatusFilter').addEventListener('change', function() {
            filterOrders();
        });

        function filterOrders() {
            var orderStatus = document.getElementById('orderStatusFilter').value;
            var paymentStatus = document.getElementById('paymentStatusFilter').value;
            var table = document.querySelector('.table');
            var trs = table.getElementsByTagName('tr');

            // Bắt đầu từ 1 để bỏ qua header
            for (var i = 1; i < trs.length; i++) {
                var orderStatusCell = trs[i].querySelector('td:nth-child(6)'); // Cột Order Status
                var paymentStatusCell = trs[i].querySelector('td:nth-child(7)'); // Cột Payment Status

                if (orderStatusCell && paymentStatusCell) {
                    var orderStatusText = orderStatusCell.textContent.trim();
                    var paymentStatusText = paymentStatusCell.textContent.trim();

                    var orderMatch = orderStatus === '' || orderStatusText === orderStatus;
                    var paymentMatch = paymentStatus === '' || paymentStatusText === paymentStatus;

                    if (orderMatch && paymentMatch) {
                        trs[i].style.display = '';
                    } else {
                        trs[i].style.display = 'none';
                    }
                }
            }
        }
    </script>

    <!-- Confirm Delete Function -->
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this order? This action cannot be undone.');
        }
    </script>
</body>
<?php
// Đóng kết nối ở đây, sau khi đã sử dụng xong
$conn->close();
?>
<script src="../../template/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="../../template/assets/vendors/chart.js/Chart.min.js"></script>
<script src="../../template/assets/js/jquery.cookie.js" type="text/javascript"></script>
<script src="../../template/assets/js/misc.js"></script>
<!-- search order -->
<script>
    document.getElementById('searchOrder').addEventListener('input', function() {
        var filter = this.value.trim().toUpperCase();
        var table = document.querySelector('.table');
        var trs = table.getElementsByTagName('tr');
        // Bắt đầu từ 1 để bỏ qua header
        for (var i = 1; i < trs.length; i++) {
            var td = trs[i].getElementsByTagName('td')[1]; // cột ORDER (thường là cột thứ 2)
            if (td) {
                var txtValue = td.textContent.trim() || td.innerText.trim();
                trs[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    });
</script>
<script></script>

</script>