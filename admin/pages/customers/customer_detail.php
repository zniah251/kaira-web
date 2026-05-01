<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

// 1. Lấy uid từ URL
$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;

// Kiểm tra uid hợp lệ
if ($uid <= 0) {
    die("Invalid customer ID."); // Dừng và báo lỗi nếu uid không hợp lệ
}

// Handle order deletion
if (isset($_POST['delete_id'])) {
    $delete_oid = intval($_POST['delete_id']);

    // Prepare a delete statement
    $delete_sql = "DELETE FROM orders WHERE oid = ? AND uid = ?";
    $stmt_delete = $conn->prepare($delete_sql);

    if ($stmt_delete === false) {
        // Handle error - log or display
        error_log("Prepare failed for delete: " . $conn->error);
        // Optionally set a session error message
        // $_SESSION['error'] = "Error preparing delete statement.";
    } else {
        $stmt_delete->bind_param("ii", $delete_oid, $uid);

        if ($stmt_delete->execute()) {
            // Check if any row was affected
            if ($stmt_delete->affected_rows > 0) {
                // Deletion successful
                // Optionally set a session success message
                // $_SESSION['success'] = "Order deleted successfully.";
            } else {
                // No row deleted - maybe oid or uid didn't match
                 error_log("No order found for deletion with OID: " . $delete_oid . " and UID: " . $uid);
                 // Optionally set a session info message
                // $_SESSION['info'] = "No matching order found for deletion.";
            }
        } else {
            // Handle execution error - log or display
            error_log("Execute failed for delete: " . $stmt_delete->error);
            // Optionally set a session error message
            // $_SESSION['error'] = "Error executing delete statement.";
        }

        $stmt_delete->close();
    }

    // Redirect back to the same page after processing
    // Preserve existing GET parameters like uid and sort/order if they exist
    $redirect_url = $_SERVER['PHP_SELF'] . '?uid=' . $uid;
    $query_params = [];
    if (isset($_GET['sort'])) $query_params['sort'] = $_GET['sort'];
    if (isset($_GET['order'])) $query_params['order'] = $_GET['order'];
    if (isset($_GET['ajax'])) $query_params['ajax'] = $_GET['ajax']; // Preserve ajax parameter for modal context

    if (!empty($query_params)) {
        $redirect_url .= '&' . http_build_query($query_params);
    }
    
    header("Location: " . $redirect_url);
    exit(); // Always exit after a header redirect
}

// 2. Lấy thông tin khách hàng
$customer_sql = "SELECT uid, uname FROM users WHERE uid = ? LIMIT 1";
$stmt_customer = $conn->prepare($customer_sql);
if ($stmt_customer === false) {
    die("Prepare failed for customer info: " . $conn->error);
}
$stmt_customer->bind_param("i", $uid);
$stmt_customer->execute();
$customer_result = $stmt_customer->get_result();
$customer = $customer_result->fetch_assoc();
$stmt_customer->close();

// Kiểm tra nếu không tìm thấy khách hàng
if (!$customer) {
    die("Customer not found.");
}

// 3. Lấy dữ liệu đơn hàng của khách hàng đó
$sql = "SELECT oid, create_at, destatus, paystatus, paymethod, totalfinal FROM orders WHERE uid = ?";

$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'oid';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'asc';

// Thêm điều kiện sắp xếp
$sql .= " ORDER BY ";
switch($sort_by) {
    case 'oid':
        $sql .= "oid " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
        break;
    case 'create_at':
        $sql .= "create_at " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
        break;
    case 'destatus':
        $sql .= "destatus " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
        break;
    case 'paystatus':
        $sql .= "paystatus " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
        break;
    case 'paymethod':
        $sql .= "paymethod " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
        break;
    case 'totalfinal':
        $sql .= "totalfinal " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
        break;
    default:
        $sql .= "oid " . ($sort_order == 'asc' ? 'ASC' : 'DESC');
}
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Query failed for order history: " . $conn->error);
}

// Kiểm tra nếu yêu cầu là AJAX
if (isset($_GET['ajax'])) {
?>
  <!-- Content for AJAX request -->
  <div class="customer-info mb-4 text-light">
      <h3>Customer Information</h3>
      <p><strong>Name:</strong> <?= htmlspecialchars($customer['uname']) ?></p>
      <!-- Removed uemail and uphone as per previous change -->
  </div>
  <table class="table table-dark table-striped table-hover">
    <thead>
      <tr>
        <th data-sort="oid" class="sortable">
          Order ID
          <span class="sort-icon <?php echo $sort_by == 'oid' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
            <i class="mdi mdi-arrow-up"></i>
            <i class="mdi mdi-arrow-down"></i>
          </span>
        </th>
        <th data-sort="create_at" class="sortable">
          Date
          <span class="sort-icon <?php echo $sort_by == 'create_at' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
            <i class="mdi mdi-arrow-up"></i>
            <i class="mdi mdi-arrow-down"></i>
          </span>
        </th>
        <th data-sort="destatus" class="sortable">
          Shipping
          <span class="sort-icon <?php echo $sort_by == 'destatus' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
            <i class="mdi mdi-arrow-up"></i>
            <i class="mdi mdi-arrow-down"></i>
          </span>
        </th>
        <th data-sort="paystatus" class="sortable">
          Payment
          <span class="sort-icon <?php echo $sort_by == 'paystatus' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
            <i class="mdi mdi-arrow-up"></i>
            <i class="mdi mdi-arrow-down"></i>
          </span>
        </th>
        <th data-sort="paymethod" class="sortable">
          Method
          <span class="sort-icon <?php echo $sort_by == 'paymethod' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
            <i class="mdi mdi-arrow-up"></i>
            <i class="mdi mdi-arrow-down"></i>
          </span>
        </th>
        <th data-sort="totalfinal" class="sortable">
          Spent
          <span class="sort-icon <?php echo $sort_by == 'totalfinal' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
            <i class="mdi mdi-arrow-up"></i>
            <i class="mdi mdi-arrow-down"></i>
          </span>
        </th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['oid']) ?></td>
                <td><?= $row['create_at'] ?></td>
                <td><span class="badge <?= str_replace(' ', '-', $row['destatus']) ?>"><?= $row['destatus'] ?></span></td>
                <td><span class="badge <?= str_replace(' ', '-', $row['paystatus']) ?>"><?= $row['paystatus'] ?></span></td>
                <td><span class="badge <?= str_replace(' ', '-', $row['paymethod']) ?>"><?= $row['paymethod'] ?></span></td>
                <td><?= number_format($row['totalfinal']) . ' VNĐ';?></td>
                <td>
                  <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= $row['oid'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xoá đơn hàng này?')">Delete</button>
                  </form>
                </td>
              </tr>
          <?php endwhile; ?>
      <?php else: ?>
          <tr><td colspan="7" class="text-center">No orders found for this customer.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
  <?php
} else {
  // Full page render (keep existing HTML structure)
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Customer Order History</title>
  <!-- Thêm Material Design Icons -->
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
  <style>
body {
  font-family: 'Times New Roman', serif;
  padding: 20px;
  background-color: #F5EBFA;
  color: #49225B;
}

    h3 {
        color: #49225B;
        margin-bottom: 15px;
    }

    .customer-info p {
        margin-bottom: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

table thead {
  background-color: transparent;
  color: #49225B;
}

th,
td {
  padding: 10px 12px;
  border-bottom: 1px solid #ffffff;
  text-align: left;
}

/* Row even */
table tbody tr:nth-child(even) {
  background-color: #F5EBFA;
}

/* Row hover */
table tbody tr:hover {
  background-color: #E7DBEF;
}

/* Header sortable */
th.sortable {
  cursor: pointer;
  position: relative;
  padding-right: 25px;
  font-weight: bold;
}

/* Sort icon */
.sort-icon {
  position: absolute;
  right: 5px;
  top: 50%;
  transform: translateY(-50%);
  color: #666;
}

.sort-icon.active {
  color: #2196F3;
}

.sort-icon .mdi-arrow-up,
.sort-icon .mdi-arrow-down {
  display: none;
}

.sort-icon.asc .mdi-arrow-up,
.sort-icon.desc .mdi-arrow-down {
  display: inline-block;
}

/* Badge chung */
.badge {
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 500;
  display: inline-block;
  color: #fff;
}

/* Shipping Status */
.Pending {
  background: #6667AB;
}
.Shipping {
  background: #6E3482;
}
.Cancelled {
  background: #420D4B;
}
.Confirmed {
  background: #210635;
}
.Delivered {
  background: #A56ABD;
  color: white;
}
.Returned {
  color: black;
}

/* Payment Status */
.Paid {
  background: #7B337E;
}
.Waiting-refund {
  background: #A56ABD;
}
.Refunded {
  background: #49225B;
}

/* Payment Methods */
.COD {
  background: #F5D5E0;
  color: #420D4B;
}
.MOMO {
  background: #E7DBEF;
  color: #49225B;
}
.Bank {
  background: #6667AB;
}
.Smart-Banking {
  background: #420D4B;
}
.Credit-Card {
  background: #210635;
}

    /* Delete button */
    .delete-btn {
      background-color: #ffd1d1;
      color: #b20000;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      font-weight: bold;
      font-family: 'Times New Roman', serif;
      transition: background-color 0.2s ease;
    }

    .delete-btn:hover {
      background-color: #ffb3b3;
      color: #8b0000;
    }

  </style>
</head>
<body>
    <div class="customer-info mb-4">
        <h3>Customer Order History</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($customer['uname']) ?></p>
        <!-- Removed uemail and uphone as per previous change -->
    </div>
    <table>
      <thead>
        <tr>
          <th data-sort="oid" class="sortable">
            Order ID
            <span class="sort-icon <?php echo $sort_by == 'oid' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
              <i class="mdi mdi-arrow-up"></i>
              <i class="mdi mdi-arrow-down"></i>
            </span>
          </th>
          <th data-sort="create_at" class="sortable">
            Date
            <span class="sort-icon <?php echo $sort_by == 'create_at' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
              <i class="mdi mdi-arrow-up"></i>
              <i class="mdi mdi-arrow-down"></i>
            </span>
          </th>
          <th data-sort="destatus" class="sortable">
            Shipping
            <span class="sort-icon <?php echo $sort_by == 'destatus' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
              <i class="mdi mdi-arrow-up"></i>
              <i class="mdi mdi-arrow-down"></i>
            </span>
          </th>
          <th data-sort="paystatus" class="sortable">
            Payment
            <span class="sort-icon <?php echo $sort_by == 'paystatus' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
              <i class="mdi mdi-arrow-up"></i>
              <i class="mdi mdi-arrow-down"></i>
            </span>
          </th>
          <th data-sort="paymethod" class="sortable">
            Method
            <span class="sort-icon <?php echo $sort_by == 'paymethod' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
              <i class="mdi mdi-arrow-up"></i>
              <i class="mdi mdi-arrow-down"></i>
            </span>
          </th>
          <th data-sort="totalfinal" class="sortable">
            Spent
            <span class="sort-icon <?php echo $sort_by == 'totalfinal' ? ($sort_order == 'asc' ? 'asc' : 'desc') : ''; ?>">
              <i class="mdi mdi-arrow-up"></i>
              <i class="mdi mdi-arrow-down"></i>
            </span>
          </th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['oid']) ?></td>
                  <td><?= $row['create_at'] ?></td>
                  <td><span class="badge <?= str_replace(' ', '-', $row['destatus']) ?>"><?= $row['destatus'] ?></span></td>
                  <td><span class="badge <?= str_replace(' ', '-', $row['paystatus']) ?>"><?= $row['paystatus'] ?></span></td>
                  <td><span class="badge <?= str_replace(' ', '-', $row['paymethod']) ?>"><?= $row['paymethod'] ?></span></td>
                  <td><?= number_format($row['totalfinal']) . ' VNĐ';?></td>
                  <td>
                    <form method="POST" style="display:inline;">
                      <input type="hidden" name="delete_id" value="<?= $row['oid'] ?>">
                      <button type="submit" class="delete-btn" onclick="return confirm('Xoá đơn hàng này?')">Delete</button>
                    </form>
                  </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" class="text-center">No orders found for this customer.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <script>
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
     // Gắn sự kiện click cho các <th> có class "sortable"
document.querySelectorAll('th.sortable').forEach(header => {
  header.style.cursor = 'pointer';
  header.addEventListener('click', function () {
    const sortValue = this.getAttribute('data-sort');
    if (sortValue) {
      changeSort(sortValue);
    }
  });
});

    </script>

</body>

</html>
<?php } ?>