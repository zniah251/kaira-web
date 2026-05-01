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

if (isset($_POST['add_user'])) {
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phonenumber'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (uname, email, address, phonenumber, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $uname, $email, $address, $phone, $password);
    $stmt->execute();
}
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $uid = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE uid = $uid");
    header("Location: ".$_SERVER['PHP_SELF']); // Tránh lỗi refresh xóa tiếp
    exit();
}
if (isset($_POST['update_user'])) {
    $uid = $_POST['uid'];
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phonenumber'];
    $rid = intval($_POST['rid']);

    $stmt = $conn->prepare("UPDATE users SET uname=?, email=?, address=?, phonenumber=?, rid=? WHERE uid=?");
    $stmt->bind_param("ssssii", $uname, $email, $address, $phone, $rid, $uid);
    $stmt->execute();
}


// 1. Xử lý tham số phân trang
$allowed_limits = [10, 25, 50, 100];
$limit = 25;
if (isset($_GET['limit']) && is_numeric($_GET['limit']) && in_array(intval($_GET['limit']), $allowed_limits)) {
    $limit = intval($_GET['limit']);
}
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get sorting parameters
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'uid';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Validate sort_by to prevent SQL injection
$allowed_sort_columns = ['uid', 'uname', 'email', 'address', 'phonenumber', 'rid', 'created_at'];
if (!in_array($sort_by, $allowed_sort_columns)) {
    $sort_by = 'uid';
}

// Validate sort_order
$sort_order = strtolower($sort_order) === 'desc' ? 'desc' : 'asc';

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

        .btn-link.custom-purple:hover,
        .btn-link.custom-gray:hover {
            opacity: 0.7;
        }

        .pagination .page-item.active .page-link {
            background-color: #6366F1;
            border-color: #6366F1;
            color: white;
        }

        .pagination .page-item .page-link {
            color: white;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        .table thead th {
            font-weight: bold;
            background: #f3f4f6;
            color: #222;
        }

        .table tbody tr {
            background: #fff;
        }

        .table tbody tr:hover {
            background: #f1f5f9;
        }

        select.form-control,
        select.btn {
            background: #23272f;
            color: #fff;
            border: 1px solid #6366f1;
        }

        input.form-control {
            background: #23272f;
            color: #fff;
            border: 1px solid #6366f1;
        }
        /* Toàn màn hình overlay */
.overlay-form {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background: rgba(0, 0, 0, 0.7);
  z-index: 9999;
  display: none;
  justify-content: center;
  align-items: center;
}

/* Khi bật form */
.overlay-form.active {
  display: flex;
}

/* Hộp form giữa màn hình */
.form-popup {
  background-color: #111;
  padding: 30px;
  border-radius: 12px;
  width: 100%;
  max-width: 400px;
  color: white;
  box-shadow: 0 0 10px #000;
  animation: slideDown 0.4s ease;
}

/* Hiệu ứng trượt xuống */
@keyframes slideDown {
  from {
    transform: translateY(-100px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Ô input đen, chữ trắng */
.form-popup input.form-control {
  background-color: #222;
  color: #fff;
  border: 1px solid #444;
}

.form-popup input.form-control::placeholder {
  color: #bbb;
}
/* Làm trắng chữ trong input tìm kiếm */
input.form-control {
  background-color: #222 !important;   /* Nền tối */
  color: #fff !important;              /* Chữ trắng */
  border: 1px solid #444 !important;   /* Viền tối */
}

/* Placeholder màu sáng hơn */
input.form-control::placeholder {
  color: #bbb !important;
}

/* Đảm bảo nền đồng bộ với productlist */
.content-wrapper,
.card,
.table tbody tr {
    background: #23272f !important;
    color: #e2e8f0 !important;
}
.table thead th {
    background: #23272f !important;
    color: #fff !important;
}
.table tbody tr:hover {
    background: #393e46 !important;
}

/* Nền đen cho toàn bộ trang */
body, .content-wrapper {
    background: #181a20 !important;
}

/* Giới hạn chiều rộng và căn giữa nội dung chính */
.content-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 0 40px 0;
    min-height: 100vh;
    background: none !important; /* Đảm bảo không bị ghi đè */
}

/* Card nổi bật trên nền đen */
.card {
    background: #23272f !important;
    border-radius: 14px;
    box-shadow: 0 4px 24px 0 #00000033;
}

.sortable {
    cursor: pointer;
    position: relative;
}

.sortable i {
    font-size: 0.8em;
    margin-left: 5px;
}

.sortable:hover {
    background-color: rgba(255, 255, 255, 0.1);
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
        <!-- Card thống kê -->
        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">
                            <div class="d-flex align-items-center align-self-start">
                                <?php
                                $user_count_sql = "SELECT COUNT(*) as total_users FROM users";
                                $user_result = $conn->query($user_count_sql);
                                $user_row = $user_result->fetch_assoc();
                                ?>
                                <h3 class="mb-0"><?php echo $user_row['total_users']; ?></h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="icon icon-box-success">
                                <span class="mdi mdi-account-multiple icon-item"></span>
                            </div>
                        </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Tổng số người dùng</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng người dùng -->
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <!-- Tìm kiếm & export -->
                    <div class="row align-items-center mb-4">
    <div class="col">
        <input type="text" id="searchUser" class="form-control todo-list-input" placeholder="Search user" style="color:white;">
    </div>
    <div class="col">
        <select class="btn btn-secondary dropdown-toggle" id="userPerPage" style="text-align: center; text-align-last: center;">
            <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
            <option value="25" <?php if ($limit == 25) echo 'selected'; ?>>25</option>
            <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
            <option value="100" <?php if ($limit == 100) echo 'selected'; ?>>100</option>
        </select>
    </div>
    <div class="col text-end">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="mdi mdi-logout"></i> Export
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                <a class="dropdown-item" href="#" onclick="openPrintTab(event)"><span class="mdi mdi-printer"></span> Print</a>
                <a class="dropdown-item" href="#" onclick="exportTableToExcel('users.xls')"><span class="mdi mdi-file-excel"></span> Excel</a>
                <a class="dropdown-item" href="#" onclick="exportTableToPDF()"><span class="mdi mdi-file-pdf"></span> PDF</a>
                <a class="dropdown-item" href="#"><span class="mdi mdi-content-copy"></span> Copy</a>
            </div>
        </div>
    </div>
</div>
                    
                    <!-- Bảng dữ liệu -->
                    <div class="table-responsive">
                        <?php
$edit_mode = false;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_mode = true;
    $edit_uid = intval($_GET['edit']);
    $edit_user = $conn->query("SELECT * FROM users WHERE uid = $edit_uid")->fetch_assoc();
}
?>
<?php if ($edit_mode): ?>
<form method="POST" class="row g-3 mb-4" action="">
    <input type="hidden" name="uid" value="<?= $edit_user['uid'] ?>">
    <div class="col-md-2"><input name="uname" value="<?= $edit_user['uname'] ?>" class="form-control" required></div>
    <div class="col-md-2"><input name="email" value="<?= $edit_user['email'] ?>" class="form-control" required></div>
    <div class="col-md-2"><input name="address" value="<?= $edit_user['address'] ?>" class="form-control"></div>
    <div class="col-md-2"><input name="phonenumber" value="<?= $edit_user['phonenumber'] ?>" class="form-control"></div>
    <div class="col-md-2"><input name="rid" type="number" value="<?= $edit_user['rid'] ?>" class="form-control" required></div>
    <div class="col-md-2">
        <button type="submit" name="update_user" class="btn" style="background:#a78bfa;color:#fff;font-weight:500;width:100%;">
    <i class="mdi mdi-content-save"></i> Cập nhật
</button>
    </div>
</form>
<?php endif; ?>

<!-- Nút mở form -->
<a class="btn" style="background:#a78bfa;color:#fff;font-weight:500;box-shadow:0 2px 8px 0 #a78bfa33;" onclick="toggleAddForm()">
    <i class='mdi mdi-plus'></i> Thêm người dùng
</a>

<!-- Overlay Form -->
<div id="addUserOverlay" class="overlay-form">
  <div class="form-popup">
    <h4 class="text-white mb-4">Thêm người dùng</h4>
    <form method="POST" action="" onsubmit="return confirm('Xác nhận thêm người dùng mới?')">
      <input name="uname" type="text" class="form-control mb-3" placeholder="Tên khách hàng" required>
      <input name="email" type="email" class="form-control mb-3" placeholder="Email" required>
      <input name="address" type="text" class="form-control mb-3" placeholder="Địa chỉ">
      <input name="phonenumber" type="text" class="form-control mb-3" placeholder="Số điện thoại">
      <input name="password" type="password" class="form-control mb-3" placeholder="Mật khẩu" required>
      <input name="rid" type="number" class="form-control mb-3" placeholder="Role ID" required>
      <div class="d-flex justify-content-between">
        <button type="submit" name="add_user" class="btn" style="background:#a78bfa;color:#fff;font-weight:500;">
            <i class='mdi mdi-plus'></i> Thêm
        </button>
        <button type="button" class="btn btn-secondary" onclick="toggleAddForm()">Hủy</button>
      </div>
    </form>
  </div>
</div>

<!-- Bảng dữ liệu -->
<table class="table">
    <thead>
      <tr>
        <th><input type="checkbox" class="form-check-input"></th>
        <th data-sort="uid" class="sortable">
            USER ID
            <?php if($sort_by == 'uid'): ?>
                <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
            <?php endif; ?>
        </th>
        <th data-sort="uname" class="sortable">
            Tên Khách Hàng
            <?php if($sort_by == 'uname'): ?>
                <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
            <?php endif; ?>
        </th>
        <th data-sort="email" class="sortable">
            Email
            <?php if($sort_by == 'email'): ?>
                <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
            <?php endif; ?>
        </th>
        <th data-sort="address" class="sortable">
            Địa chỉ
            <?php if($sort_by == 'address'): ?>
                <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
            <?php endif; ?>
        </th>
        <th data-sort="phonenumber" class="sortable">
            Số điện thoại
            <?php if($sort_by == 'phonenumber'): ?>
                <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
            <?php endif; ?>
        </th>
        <th data-sort="rid" class="sortable">
            Role ID
            <?php if($sort_by == 'rid'): ?>
                <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
            <?php endif; ?>
        </th>
        <th data-sort="created_at" class="sortable">
            Ngày tạo
            <?php if($sort_by == 'created_at'): ?>
                <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
            <?php endif; ?>
        </th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
        <?php
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_sql = '';
if ($search !== '') {
    $search_escaped = $conn->real_escape_string($search);
    $search_sql = "WHERE uname LIKE '%$search_escaped%' OR phonenumber LIKE '%$search_escaped%'";
}

// Đếm tổng số người dùng (để phân trang)
$count_sql = "SELECT COUNT(*) as total FROM users $search_sql";
$total_result = $conn->query($count_sql);
$total_row = $total_result->fetch_assoc();
$total_users = $total_row['total'];
$total_pages = max(1, ceil($total_users / $limit));

// Lấy dữ liệu người dùng theo trang
$user_sql = "SELECT uid, uname, email, address, phonenumber, rid, created_at FROM users $search_sql ORDER BY $sort_by $sort_order LIMIT $limit OFFSET $offset";
$user_result = $conn->query($user_sql);

// Hiển thị từng dòng
if ($user_result->num_rows > 0) {
    while ($row = $user_result->fetch_assoc()) {
        echo "<tr>
    <td>
        <div class='form-check form-check-muted m-0'>
            <label class='form-check-label'><input type='checkbox' class='form-check-input'></label>
        </div>
    </td>
    <td>{$row['uid']}</td>
    <td>{$row['uname']}</td>
    <td>{$row['email']}</td>
    <td>{$row['address']}</td>
    <td>{$row['phonenumber']}</td>
    <td>{$row['rid']}</td>
    <td>{$row['created_at']}</td>
    <td>
        <div class='d-flex align-items-center gap-2'>
            <a href='?edit={$row['uid']}' class='btn btn-link custom-purple p-0' title='Sửa'>
                <i class='mdi mdi-pencil'></i>
            </a>
            <a href='?delete={$row['uid']}' class='btn btn-link custom-gray p-0' title='Xóa' onclick='return confirm(\"Xóa người dùng này?\")'>
                <i class='mdi mdi-close-circle'></i>
            </a>
        </div>
    </td>
</tr>";
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>Không có người dùng nào</td></tr>";
}
?>
    </tbody>
</table>
<div class="d-flex justify-content-end mt-3">
    <nav aria-label="User list pagination">
        <ul class="pagination mb-0">
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>">«</a>
            </li>
            <?php
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
                $active = ($i == $page) ? 'active' : '';
                echo "<li class='page-item $active'><a class='page-link' href='?page=$i&limit=$limit&search=" . urlencode($search) . "'>$i</a></li>";
            }
            ?>
            <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>">»</a>
            </li>
        </ul>
    </nav>
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- end content-wrapper -->
                                 
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
<script>
function toggleAddForm() {
    const overlay = document.getElementById('addUserOverlay');
    overlay.classList.toggle('active');
}

// Đóng form khi click ra ngoài
document.getElementById('addUserOverlay').addEventListener('click', function (e) {
    if (e.target === this) {
        this.classList.remove('active');
    }
});

// Đóng form bằng phím ESC
document.addEventListener('keydown', function (e) {
    if (e.key === "Escape") {
        document.getElementById('addUserOverlay').classList.remove('active');
    }
});
</script>
<script>
// Tìm kiếm user theo tên hoặc số điện thoại (cột 3 và 6)
document.getElementById('searchUser').addEventListener('input', function() {
    var filter = this.value.trim().toUpperCase();
    var table = document.querySelector('.table');
    var trs = table.getElementsByTagName('tr');
    for (var i = 1; i < trs.length; i++) {
        var tdName = trs[i].getElementsByTagName('td')[2]; // Tên
        var tdPhone = trs[i].getElementsByTagName('td')[5]; // Số điện thoại
        var txtValue = '';
        if (tdName) txtValue += tdName.textContent.trim();
        if (tdPhone) txtValue += ' ' + tdPhone.textContent.trim();
        trs[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
    }
});

// Chọn số lượng hiển thị/trang
document.getElementById('userPerPage').addEventListener('change', function() {
    const limit = this.value;
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('limit', limit);
    urlParams.set('page', 1); // Reset về trang 1 khi đổi limit
    window.location.search = urlParams.toString();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers to all sortable headers
    document.querySelectorAll('th.sortable').forEach(th => {
        th.addEventListener('click', function() {
            const sortBy = this.dataset.sort;
            const currentOrder = new URLSearchParams(window.location.search).get('order');
            const newOrder = (!currentOrder || currentOrder === 'desc') ? 'asc' : 'desc';
            
            // Get current URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('sort', sortBy);
            urlParams.set('order', newOrder);
            
            // Redirect with new sorting parameters
            window.location.search = urlParams.toString();
        });
    });
});
</script>
