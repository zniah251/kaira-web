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

// Thêm xử lý xóa ở đầu file, sau phần session_start
if (isset($_POST['delete_customer']) && isset($_POST['customer_id'])) {
    $customer_id = intval($_POST['customer_id']);
    
    // Bắt đầu transaction
    $conn->begin_transaction();
    
    try {
        // Xóa các đơn hàng của khách hàng trước
        $delete_orders = "DELETE FROM orders WHERE uid = ?";
        $stmt_orders = $conn->prepare($delete_orders);
        $stmt_orders->bind_param("i", $customer_id);
        $stmt_orders->execute();
        
        // Sau đó xóa khách hàng
        $delete_user = "DELETE FROM users WHERE uid = ?";
        $stmt_user = $conn->prepare($delete_user);
        $stmt_user->bind_param("i", $customer_id);
        $stmt_user->execute();
        
        // Nếu mọi thứ OK, commit transaction
        $conn->commit();
        $_SESSION['success'] = "Xóa khách hàng thành công!";
    } catch (Exception $e) {
        // Nếu có lỗi, rollback
        $conn->rollback();
        $_SESSION['error'] = "Lỗi khi xóa khách hàng: " . $e->getMessage();
    }
    
    // Chuyển hướng để tránh resubmit form
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . http_build_query($_GET));
    exit();
}

// 1. Xử lý tham số phân trang
$allowed_limits = [25, 50, 100];
$limit = 25;
if (isset($_GET['limit']) && is_numeric($_GET['limit']) && in_array(intval($_GET['limit']), $allowed_limits)) {
  $limit = intval($_GET['limit']);
}
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Thêm biến sort ở đầu file, sau phần xử lý limit
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'uid';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Thêm biến search ở đầu file, sau phần xử lý sort
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 2. Truy vấn tổng số khách hàng (có filter)
$total_customers_sql = "SELECT COUNT(DISTINCT u.uid) AS total_count FROM users u INNER JOIN orders o ON u.uid = o.uid WHERE 1=1 ";

// Thêm điều kiện tìm kiếm nếu có
if (!empty($search)) {
    $search_param = "%" . $search . "%";
    $total_customers_sql .= " AND u.uname LIKE ? ";
}

$stmt_total = $conn->prepare($total_customers_sql);
if (!empty($search)) {
    $stmt_total->bind_param("s", $search_param);
}
$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_row = $total_result->fetch_assoc();
$total_customers = $total_row['total_count'];
$stmt_total->close();

// 3. Tính tổng số trang
$total_pages = max(1, ceil($total_customers / $limit));

// 4. Đảm bảo trang hiện tại không vượt quá tổng số trang
if ($page > $total_pages && $total_pages > 0) {
  $page = $total_pages;
  $offset = ($page - 1) * $limit;
} elseif ($page < 1) {
  $page = 1;
  $offset = 0;
}

// 5. Tính chỉ số hiển thị
$start_entry = $total_customers > 0 ? $offset + 1 : 0;
$end_entry = min($offset + $limit, $total_customers);

// Kiểm tra kết nối database
if (!$conn) {
  die("Lỗi kết nối database: " . mysqli_connect_error());
}

// Kiểm tra câu truy vấn
$test_query = "SELECT COUNT(*) as total FROM users";
$test_result = $conn->query($test_query);
if ($test_result) {
  $row = $test_result->fetch_assoc();
  echo "<!-- Số lượng users trong database: " . $row['total'] . " -->";
} else {
  echo "<!-- Lỗi kiểm tra users: " . $conn->error . " -->";
}

$test_query2 = "SELECT COUNT(*) as total FROM orders";
$test_result2 = $conn->query($test_query2);
if ($test_result2) {
  $row = $test_result2->fetch_assoc();
  echo "<!-- Số lượng orders trong database: " . $row['total'] . " -->";
} else {
  echo "<!-- Lỗi kiểm tra orders: " . $conn->error . " -->";
}

// Kiểm tra cấu trúc bảng
$test_query3 = "DESCRIBE orders";
$test_result3 = $conn->query($test_query3);
if ($test_result3) {
  echo "<!-- Cấu trúc bảng orders: -->";
  while ($row = $test_result3->fetch_assoc()) {
    echo "<!-- Cột: " . $row['Field'] . " - Kiểu: " . $row['Type'] . " -->";
  }
} else {
  echo "<!-- Lỗi kiểm tra cấu trúc orders: " . $conn->error . " -->";
}

// Sửa lại câu truy vấn SQL để thêm điều kiện tìm kiếm
$sql_customers = "SELECT
    u.uname,
    u.uid,
    COUNT(u.uid) AS orders,
    SUM(o.totalfinal) AS spents
FROM
    users u
INNER JOIN
    orders o ON u.uid = o.uid
WHERE 1=1 ";

// Thêm điều kiện tìm kiếm nếu có
if (!empty($search)) {
    $sql_customers .= " AND u.uname LIKE ? ";
}

$sql_customers .= "GROUP BY
    u.uid, u.uname
HAVING
    COUNT(*) > 0
ORDER BY ";

// Thêm điều kiện sắp xếp
switch($sort_by) {
    case 'name':
        $sql_customers .= "u.uname " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
        break;
    case 'order':
        $sql_customers .= "orders " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
        break;
    case 'spent':
        $sql_customers .= "spents " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
        break;
    default:
        $sql_customers .= "u.uid " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
}

$sql_customers .= " LIMIT ?, ?;";

$stmt_customers = $conn->prepare($sql_customers);
if ($stmt_customers === false) {
  die("Lỗi chuẩn bị câu truy vấn: " . $conn->error);
}

if (!empty($search)) {
    $stmt_customers->bind_param("sii", $search_param, $offset, $limit);
} else {
    $stmt_customers->bind_param("ii", $offset, $limit);
}

$stmt_customers->execute();
$result_customers = $stmt_customers->get_result();

$customers = [];
if ($result_customers) {
  while ($row_customers = $result_customers->fetch_assoc()) {
    $customers[] = $row_customers;
  }
} else {
  error_log("Lỗi truy vấn khách hàng: " . $conn->error);
}

// 8. Truy vấn tỉ lệ khách hàng trên người dùng (có filter)
$total_users_sql = "SELECT COUNT(DISTINCT u.uid) AS total_users_count FROM users u";
$stmt_users_total = $conn->prepare($total_users_sql);

if (!empty($where_params)) {
  $stmt_users_total->bind_param($where_types, ...$where_params);
}
$stmt_users_total->execute();
$totalusers_result = $stmt_users_total->get_result();
$totalusers_row = $totalusers_result->fetch_assoc();
$total_users = $totalusers_row['total_users_count'];
$total_ratio = ($total_users == 0) ? 0 : number_format(($total_customers / $total_users * 100), 2);
$stmt_users_total->close();

// 9. Truy vấn số lượng khách hàng quay lại
$returning_sql = "SELECT COUNT(*) as returning_count 
FROM (
    SELECT u.uid
    FROM users u 
    INNER JOIN orders o ON u.uid = o.uid 
    GROUP BY u.uid 
    HAVING COUNT(o.oid) > 1
) as returning_customers";

$stmt_returning = $conn->prepare($returning_sql);
if (!empty($where_params)) {
  $stmt_returning->bind_param($where_types, ...$where_params);
}
$stmt_returning->execute();
$returning_result = $stmt_returning->get_result();
$returning_row = $returning_result->fetch_assoc();
$total_returning = $returning_row['returning_count'];
$final = ($total_returning == 0) ? 0 : $total_returning;
$stmt_returning->close();


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Corona Admin</title>
  <link rel="stylesheet" href="../../../admin/template/assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../../admin/template/assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../../../admin/template/assets/vendors/select2/select2.min.css">
  <link rel="stylesheet" href="../../../admin/template/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="../../template/assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="../../template/assets/vendors/owl-carousel-2/owl.carousel.min.css">
  <link rel="stylesheet" href="../../template/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
  <link rel="stylesheet" href="../../assets/product.css">
  <link rel="stylesheet" href="../../../admin/template/assets/css/style.css">
  <link rel="shortcut icon" href="../../../admin/template/assets/images/favicon.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body, .container-scroller, .card, .form-control, .form-select, .custom-table, .custom-table th, .custom-table td, .btn, .form-label, .modal-content, .modal-title, .modal-body, .modal-footer {
    font-family: 'Times New Roman', Times, serif !important;
}

    body {
      background-color: #1a1a1a;

    }

    .container-scroller {
      background-color: #1a1a1a;
    }

    .card {
      background-color: #1f1f1f;
      border: 1px solid #333;
      border-radius: 12px;
      padding: 24px;
    }

    .form-control,
    .form-select {
      background-color: #2d2d2d;
      border: 1px solid #3d3d3d;
      color: #ffffff;
    }

    .form-control:focus,
    .form-select:focus {
      background-color: #2d2d2d;
      color: #ffffff;
    }

    .custom-table-container {
      width: 100%;
      margin-top: 20px;
    }

    .custom-table th,
    .custom-table td {
      padding: 12px 8px;
      vertical-align: middle;
      text-align: left;
    }

    .custom-table th {
      color: #ccc;
      font-weight: 600;
    }

    .custom-table thead {
      color: #d1c0ec !important;
    }

    .form-select.d-inline-block.w-auto {
      width: 60px !important;
      vertical-align: middle !important;
      height: 32px !important;
      padding: 2px 8px !important;
      font-size: 13px !important;
    }

    .btn.btn-primary {
      background-color: #7b3aed;
      border-color: transparent !important;
      color: #fff !important;
      font-size: 13px;
      padding: 8px 15px;
      border-radius: 6px;
    }

    .btn.btn-secondary.mx-2 {
      font-size: 13px;
      padding: 7px 10px;
      text-align: center;
      background-color: #5c5c5c;
      border: none;
      color: #fff;
      border-radius: 6px;
      padding-top: 8.5px;
      padding-bottom: 8.5px;
    }

    .custom-table tbody tr {
      border-bottom: 1px solid #44475a;
    }

    .custom-table tbody tr:last-child {
      border-bottom: none;
    }

    .custom-table thead tr {
      border-bottom: 2px solid #44475a;
    }

    .sortable {
        position: relative;
        cursor: pointer;
    }
    .sortable:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    .sortable i {
        margin-left: 5px;
    }

    /* Style cho form tìm kiếm */
    .form-control:focus {
        border-color: #8d769a !important;
        box-shadow: 0 0 0 0.2rem rgba(123, 58, 237, 0.25);
    }
    
    .btn-primary {
        background-color: #8d769a;
        border-color: #8d769a;
    }
    
    .btn-primary:hover {
        background-color: #8d769a;
        border-color: #8d769a;
    }
    
    .btn-secondary {
        background-color: #b3a3ba;
        border-color:  #b3a3ba;
    }
    
    .btn-secondary:hover {
        background-color: #b3a3ba;
        border-color:  #b3a3ba;
    }

    /* Style cho select box Sort by */
    .form-select.d-inline-block.w-auto.mx-2 {
        width: 150px !important; /* Tăng chiều rộng */
        min-width: 150px;
        padding: 8px 12px;
        font-size: 14px;
        height: auto;
        background-color: #2d2d2d;
        border: 1px solid #3d3d3d;
        color: #ffffff;
    }

    .form-select.d-inline-block.w-auto.mx-2:focus {
        border-color: #8d769a !important;
        box-shadow: 0 0 0 0.2rem rgba(141, 118, 154, 0.25);
    }

    /* Style cho select box Limit */
    #limitSelect {
        width: 80px !important;
        min-width: 80px;
        padding: 8px 12px;
        font-size: 14px;
        height: auto;
        background-color: #2d2d2d;
        border: 1px solid #3d3d3d;
        color: #ffffff;
    }

    #limitSelect:focus {
        border-color: #8d769a !important;
        box-shadow: 0 0 0 0.2rem rgba(141, 118, 154, 0.25);
    }

    /* Style cho nút xóa */
    .btn-link.text-danger {
        color: #dc3545 !important;
        text-decoration: none;
    }
    
    .btn-link.text-danger:hover {
        color: #c82333 !important;
    }
    
    .mdi-close-circle {
        font-size: 1.2rem;
    }

    /* Style cho icon dấu cộng */
    .btn-link.custom-purple {
        color: #8d769a !important;
        text-decoration: none;
    }
    
    .btn-link.custom-purple:hover {
        color: #7a6685 !important;
    }
    
    .mdi-plus-circle {
        font-size: 1.2rem;
    }

    /* Style cho icon dấu nhân */
    .btn-link.custom-gray {
        color: #D9D8D9 !important;
        text-decoration: none;
    }
    
    .btn-link.custom-gray:hover {
        color: #c4c3c4 !important;
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

          <!-- Nội dung chính -->
          <div class="container-fluid">
            <div class="grid grid-cols-3 gap-4 mb-6">
              <!-- Tổng số khách hàng -->
              <div class="flex items-center justify-between p-4 rounded-lg shadow border border-gray-700" style="background-color: #8d769a;">
                <div>
                  <h2 class="text-2xl font-semibold text-white"><?php echo $total_customers ?></h2>
                  <p class="text1">Total Customers</p>
                </div>
                <div class="bg-green-900 p-2 rounded-lg">
                  <i class="fa-solid fa-users text-green-400 text-xl"></i>
                </div>
              </div>

              <!-- Tỉ lệ khách hàng/người dùng -->
              <div class="flex items-center justify-between p-4 rounded-lg shadow border border-gray-700" style="background-color: #8d769a;">
                <div>
                  <h2 class="text-2xl font-semibold text-white"> <?php echo $total_ratio ?>%</h2>
                  <p class="text1">Customer/User Ratio</p>
                </div>
                <div class="bg-blue-900 p-2 rounded-lg">
                  <i class="fa-solid fa-chart-pie text-blue-400 text-xl"></i>
                </div>
              </div>

              <!-- Khách hàng quay lại -->
              <div class="flex items-center justify-between p-4 rounded-lg shadow border border-gray-700" style="background-color: #8d769a;">
                <div>
                  <h2 class="text-2xl font-semibold text-white"><?php echo $total_returning; ?></h2>
                  <p class="text1">Returning Customers (2+ Orders)</p>
                </div>
                <div class="bg-yellow-900 p-2 rounded-lg">
                  <i class="fa-solid fa-rotate-left text-yellow-400 text-xl"></i>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                  <form method="GET" class="d-flex align-items-center" style="width: 25%;">
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           placeholder="Search Customer" 
                           value="<?php echo htmlspecialchars($search); ?>"
                           id="searchInput">
                    </button>
                    <?php if (!empty($search)): ?>
                      <a href="?<?php echo http_build_query(array_merge($_GET, ['search' => ''])); ?>" 
                         class="btn btn-secondary ms-2">
                        <i class="mdi mdi-close"></i>
                      </a>
                    <?php endif; ?>
                    <!-- Giữ lại các tham số khác trong URL -->
                    <?php
                    foreach ($_GET as $key => $value) {
                        if ($key !== 'search' && $key !== 'page') {
                            echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                        }
                    }
                    ?>
                  </form>
                  <div>
                    <select class="form-select d-inline-block w-auto" id="limitSelect" onchange="changeLimit(this.value)">
                      <option value="25" <?php if ($limit == 25) echo 'selected'; ?>>25</option>
                      <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
                      <option value="100" <?php if ($limit == 100) echo 'selected'; ?>>100</option>
                    </select>
                    <select class="form-select d-inline-block w-auto mx-2" id="sortSelect" onchange="changeSort(this.value)">
                      <option value="" disabled <?php echo !isset($_GET['sort']) ? 'selected' : ''; ?>>Sort by</option>
                      <option value="uid" <?php echo ($sort_by == 'uid') ? 'selected' : ''; ?>>Customer ID</option>
                      <option value="name" <?php echo ($sort_by == 'name') ? 'selected' : ''; ?>>Customer Name</option>
                      <option value="order" <?php echo ($sort_by == 'order') ? 'selected' : ''; ?>>Order</option>
                      <option value="spent" <?php echo ($sort_by == 'spent') ? 'selected' : ''; ?>>Total Spent</option>
                    </select>

                    <button class="btn btn-secondary mx-2" style="background-color: #8d769a !important;">Export</button>
         
                  </div>
                </div>

                <div class="custom-table-container">
                  <table class="table align-middle custom-table">
                    <thead>
                      <tr>
                        <th>STT</th>
                        <th data-sort="name" class="sortable">Customer 
                          <?php if($sort_by == 'name'): ?>
                            <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
                          <?php endif; ?>
                        </th>
                        <th data-sort="uid" class="sortable">Customer ID
                          <?php if($sort_by == 'uid'): ?>
                            <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
                          <?php endif; ?>
                        </th>
                        <th data-sort="order" class="sortable">Order
                          <?php if($sort_by == 'order'): ?>
                            <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
                          <?php endif; ?>
                        </th>
                        <th data-sort="spent" class="sortable">Total Spent
                          <?php if($sort_by == 'spent'): ?>
                            <i class="mdi mdi-arrow-<?php echo $sort_order == 'asc' ? 'up' : 'down'; ?>"></i>
                          <?php endif; ?>
                        </th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $stt = ($page - 1) * $limit + 1;
                      if (!empty($customers)) {
                        foreach ($customers as $customer) {
                      ?>
                        <tr>
                          <td><?php echo $stt++; ?></td>
                          <td><?php echo htmlspecialchars($customer['uname']); ?></td>
                          <td><?php echo htmlspecialchars($customer['uid']); ?></td>
                          <td><?php echo htmlspecialchars($customer['orders']); ?></td>
                          <td><?php echo number_format($customer['spents'], 0, ',', '.') . ' VNĐ'; ?></td>
                          <td>
                            <div class="d-flex gap-2">
                              <button type="button" 
                                      class="btn btn-link custom-purple p-0" 
                                      data-bs-toggle="modal" 
                                      data-bs-target="#customerDetailModal"
                                      onclick="viewCustomer(<?php echo $customer['uid']; ?>)">
                                <i class="mdi mdi-plus-circle"></i>
                              </button>
                              <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                <input type="hidden" name="customer_id" value="<?php echo $customer['uid']; ?>">
                                <button type="submit" name="delete_customer" class="btn btn-link custom-gray p-0">
                                  <i class="mdi mdi-close-circle"></i>
                                </button>
                              </form>
                            </div>
                          </td>
                        </tr>
                      <?php
                        }
                      } else {
                        echo '<tr><td colspan="6" class="text-center">Không có khách hàng nào được tìm thấy.</td></tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>

              </div>
            </div>
          </div>
          <!-- Kết thúc nội dung chính -->

        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../../template/assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="../../template/assets/vendors/chart.js/Chart.min.js"></script>
  <script src="../../template/assets/vendors/progressbar.js/progressbar.min.js"></script>
  <script src="../../template/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
  <script src="../../template/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <script src="../../template/assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
  <script src="../../template/assets/js/jquery.cookie.js"></script>
  <script src="../../template/assets/js/off-canvas.js"></script>
  <script src="../../template/assets/js/hoverable-collapse.js"></script>
  <script src="../../template/assets/js/misc.js"></script>
  <script src="../../template/assets/js/settings.js"></script>
  <script src="../../template/assets/js/todolist.js"></script>
  <script src="../../template/assets/js/dashboard.js"></script>
  <script>
    function changeLimit(newLimit) {
      // Lấy URL hiện tại
      let currentUrl = new URL(window.location.href);
      
      // Cập nhật hoặc thêm tham số limit
      currentUrl.searchParams.set('limit', newLimit);
      
      // Reset về trang 1 khi thay đổi limit
      currentUrl.searchParams.set('page', '1');
      
      // Chuyển hướng đến URL mới
      window.location.href = currentUrl.toString();
    }

    // Xử lý sự kiện khi trang được tải
    document.addEventListener('DOMContentLoaded', function() {
      // Lấy select box
      const limitSelect = document.getElementById('limitSelect');
      
      // Đảm bảo giá trị được chọn khớp với limit hiện tại
      const currentLimit = new URLSearchParams(window.location.search).get('limit');
      if (currentLimit) {
        limitSelect.value = currentLimit;
      }
    });

    function changeSort(newSort) {
        // Lấy URL hiện tại
        let currentUrl = new URL(window.location.href);
        
        // Nếu đã chọn sort này rồi, đổi order
        if (currentUrl.searchParams.get('sort') === newSort) {
            const currentOrder = currentUrl.searchParams.get('order');
            currentUrl.searchParams.set('order', currentOrder === 'asc' ? 'desc' : 'asc');
        } else {
            // Nếu chọn sort mới, set order mặc định là asc
            currentUrl.searchParams.set('sort', newSort);
            currentUrl.searchParams.set('order', 'asc');
        }
        
        // Chuyển hướng đến URL mới
        window.location.href = currentUrl.toString();
    }

    // Thêm xử lý khi trang được tải
    document.addEventListener('DOMContentLoaded', function() {
        // ... existing DOMContentLoaded code ...

        // Xử lý sort select
        const sortSelect = document.getElementById('sortSelect');
        const currentSort = new URLSearchParams(window.location.search).get('sort');
        if (currentSort) {
            sortSelect.value = currentSort;
        }

        // Thêm sự kiện click cho các header của bảng
        document.querySelectorAll('.custom-table th').forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const sortValue = this.getAttribute('data-sort');
                if (sortValue) {
                    changeSort(sortValue);
                }
            });
        });

        // Thêm xử lý cho tìm kiếm
        const searchInput = document.getElementById('searchInput');
        const searchForm = searchInput.closest('form');
        
        // Tự động submit form khi nhập (với delay)
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchForm.submit();
            }, 500); // Delay 500ms sau khi người dùng ngừng nhập
        });

        // Xử lý phím Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    });

    function confirmDelete() {
        return confirm('Bạn có chắc chắn muốn xóa khách hàng này? Hành động này sẽ xóa tất cả đơn hàng của khách hàng và không thể hoàn tác!');
    }

    // Hiển thị thông báo
    <?php if (isset($_SESSION['success'])): ?>
        alert('<?php echo $_SESSION['success']; ?>');
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        alert('<?php echo $_SESSION['error']; ?>');
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    function viewCustomer(uid) {
      // Mở cửa sổ nhỏ với trang chi tiết khách hàng
      const width = 800; // Kích thước cửa sổ
      const height = 600;
      const left = (screen.width / 2) - (width / 2);
      const top = (screen.height / 2) - (height / 2);
      const features = `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`;

      // Mở trang customer_detail.php trong cửa sổ mới và truyền uid qua URL
      window.open('customer_detail.php?uid=' + uid, '_blank', features);
    }

</script>
</body>

</html>