<?php
session_start();
$cart_items = $_SESSION['cart'] ?? [];

// Check if user is logged in
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    header("Location: /e-web/user/page/sign-in/login2.php");
    exit();
}

require_once("../../../connect.php");

// Fetch user information
$user_info = [
    'uname' => '',
    'email' => '',
    'phonenumber' => '',
    'address' => '',
    'balance' => 1000000 // Default balance for new users
];

if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    $stmt = $conn->prepare("SELECT uname, email, phonenumber, address, balance FROM users WHERE uid = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $db_user_info = $result->fetch_assoc();

    if ($db_user_info) {
        $user_info = $db_user_info;
        // If balance is null (new user), set default value
        if ($user_info['balance'] === null) {
            $user_info['balance'] = 1000000;
        }
    }
    $stmt->close();
}

$voucherData = null;

// Load danh sách voucher
$voucherList = [];
// Lấy danh sách voucher mà user này sở hữu và còn hạn
$sql = "SELECT v.vid, v.name, v.discount, v.minprice, v.expiry 
        FROM voucher v
        INNER JOIN user_voucher uv ON v.vid = uv.vid
        WHERE uv.uid = ? AND v.expiry >= CURDATE() AND uv.status = 'unused'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
$voucherList = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $voucherList[] = $row;
  }
}
$stmt->close();

// Tính toán giá giỏ hàng
$total = 0;
foreach ($cart_items as $item) {
  $total += $item['price'] * $item['quantity'];
}

$voucherName = $_POST['voucher'] ?? $_GET['voucher'] ?? '';
$shipping = 30000;
$discount = 0;

$voucherMessage = '';

if ($voucherName !== '') {
  $stmt = $conn->prepare(
    "SELECT v.* FROM voucher v
     INNER JOIN user_voucher uv ON v.vid = uv.vid
     WHERE v.name = ? AND uv.uid = ? AND v.expiry >= CURDATE() AND uv.status = 'unused'"
  );
  $stmt->bind_param("si", $voucherName, $uid);
  $stmt->execute();
  $voucherData = $stmt->get_result()->fetch_assoc();
  $stmt->close();

if ($voucherData) {
    if ($total >= $voucherData['minprice']) {
      if (strtolower(trim($voucherData['name'])) === 'free shipping') {
          $shipping = 0;
      } else {
          $discount = $total * ($voucherData['discount'] / 100);
      }
    } else {
      $voucherMessage = "Đơn hàng của bạn chưa đạt tối thiểu " 
        . number_format($voucherData['minprice'], 0, ',', '.') 
        . "đ để áp dụng mã <strong>" . htmlspecialchars($voucherData['name']) . "</strong>.";
      $voucherName = ''; // Huỷ mã không hợp lệ
    }
  }
}


$total_final = $total + $shipping - $discount;

// If form is submitted, update user information
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
    $new_fullname = trim($_POST['fullname'] ?? '');
    $new_address = trim($_POST['address'] ?? '');
    $new_phone = trim($_POST['phone'] ?? '');

    // Update user information in database
    $update_stmt = $conn->prepare("UPDATE users SET uname = ?, address = ?, phonenumber = ? WHERE uid = ?");
    $update_stmt->bind_param("sssi", $new_fullname, $new_address, $new_phone, $uid);
    
    if ($update_stmt->execute()) {
        // Update the user_info array with new values
        $user_info['uname'] = $new_fullname;
        $user_info['address'] = $new_address;
        $user_info['phonenumber'] = $new_phone;
    }
    $update_stmt->close();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thanh toán - KAIRA</title>
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
  <style>
    body {
      font-family: 'Times New Roman', Times, serif !important;
      background-color: #fff;
      color: #000;
    }
     h1,h2,h3, h4, h5 {
            font-family: 'Times New Roman', Times, serif !important;
            /* Sử dụng font Times New Roman cho tiêu đề */
        }
    .container-custom {
      max-width: 1400px;
      margin: 40px auto;
      display: flex;
      gap: 40px;
    }
    .payment-left {
      flex: 2;
    }
    .cart-right {
      flex: 1;
      background: #fff;
      padding: 20px;
      border-radius: 0;
    }
    .cart-right .product-item {
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid #ddd;
      padding: 10px 0;
    }
    .cart-right .total {
      font-weight: bold;
      font-size: 18px;
      margin-top: 10px;
    }
    .form-section {
      margin-bottom: 25px;
    }
    .form-section h5 {
      margin-bottom: 15px;
      font-weight: bold;
    }
    .form-control {
      font-family: 'Times New Roman', Times, serif !important;
    }
    .btn-primary {
      font-weight: bold;
      font-family: 'Times New Roman', Times, serif !important;
      background-color: #000;
      border: none;
      transition: 0.3s;
    }
    .btn-primary:hover {
      background-color: #333;
    }
    .btn-secondary {
      font-family: 'Times New Roman', Times, serif !important;
    }
    .btn-outline-secondary {
      font-family: 'Times New Roman', Times, serif !important;
    }
    label input[type="radio"] {
      margin-right: 5px;
    }
    input[type="radio"] + label img {
      border: 2px solid transparent;
      border-radius: 12px;
      cursor: pointer;
      transition: 0.3s ease;
      filter: grayscale(100%);
      opacity: 0.8;
    }
    input[type="radio"]:checked + label img {
      border: 2px solid #000;
      filter: none;
      opacity: 1;
      transform: scale(1.05);
    }

    /* Thêm style cho icon ví điện tử */
    #wallet + label img {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 8px;
      padding: 5px;
    }

    #wallet:checked + label img {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: 2px solid #000;
      filter: none;
      opacity: 1;
      transform: scale(1.05);
    }
    
    /* Đảm bảo tất cả text đều sử dụng Times New Roman */
    p, span, div, small, label, .alert, .form-label {
      font-family: 'Times New Roman', Times, serif !important;
    }
  </style>
</head>
<body>
<?php include('../../../navbar.php'); ?>
<div class="container-custom">
  <!-- Thanh toán -->
  <div class="payment-left">
    <h2 class="mb-4">Thanh toán</h2>
    <div id="update-message" class="alert alert-success" style="display: none;">
      Thông tin đã được cập nhật thành công!
    </div>
    
    <?php if (isset($_GET['error']) && $_GET['error'] === 'insufficient_balance'): ?>
    <div class="alert alert-danger">
      <i class="fa fa-exclamation-triangle me-2"></i>
      <strong>Số dư ví điện tử không đủ!</strong><br>
      Số dư hiện tại: <?= number_format($_GET['current'] ?? 0, 0, ',', '.') ?>đ<br>
      Số tiền cần thanh toán: <?= number_format($_GET['required'] ?? 0, 0, ',', '.') ?>đ<br>
      Vui lòng chọn phương thức thanh toán khác hoặc nạp thêm tiền vào ví.
    </div>
    <?php endif; ?>
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
      <input type="hidden" name="update_info" value="1">
      <div class="form-section">
        <h5>Thông tin giao hàng</h5>
        <input type="text" name="fullname" placeholder="Họ và tên" class="form-control mb-3" required value="<?= htmlspecialchars($user_info['uname']) ?>">
        <input type="text" name="address" placeholder="Địa chỉ" class="form-control mb-3" required value="<?= htmlspecialchars($user_info['address']) ?>">
        <input type="tel" name="phone" placeholder="Số điện thoại" class="form-control mb-3" required value="<?= htmlspecialchars($user_info['phonenumber']) ?>">
        <div class="alert alert-info">
          <i class="fa fa-wallet me-2"></i>
          <strong>Số dư ví điện tử:</strong> <?= number_format($user_info['balance'], 0, ',', '.') ?>đ
        </div>
      </div>
      <div class="form-section">
  <h5>Mã giảm giá</h5>
  <button type="button" class="btn btn-outline-secondary mb-2" id="btn-choose-voucher">Chọn mã giảm giá</button>
 <?php if (!empty($voucherMessage)): ?>
  <div class="alert alert-warning mt-2">
    <?= $voucherMessage ?>
  </div>
<?php endif; ?>


  <div id="selected-voucher" class="mb-2 text-success fw-bold"></div>
  <input type="hidden" name="voucher" id="voucher-hidden" value="<?= htmlspecialchars($voucherName) ?>">
  <div id="voucher-list" class="border p-3 rounded bg-light" style="display: none;">
  <?php if (empty($voucherList)): ?>
    <div class="alert alert-warning mb-0">Hiện tại bạn chưa lưu voucher</div>
  <?php else: ?>
    <?php foreach ($voucherList as $v): ?>
      <div class="form-check mb-2 bg-white p-3 rounded shadow-sm">
        <input class="form-check-input" type="radio" name="voucher_select" value="<?= htmlspecialchars($v['name']) ?>" id="voucher_<?= $v['vid'] ?>">
        <label class="form-check-label" for="voucher_<?= $v['vid'] ?>">
          <strong><?= htmlspecialchars($v['name']) ?></strong><br>
          Đơn hàng từ <?= number_format($v['minprice'], 0, ',', '.') ?>đ<br>
          <?php if (strtolower(trim($v['name'])) === 'free shipping'): ?>
            Giảm <strong>không giới hạn</strong><br>
          <?php else: ?>
            Giảm <?= number_format($v['discount'], 0) ?>%<br>
          <?php endif; ?>
          <em class="text-muted">HSD đến: <?= date('d-m-Y', strtotime($v['expiry'])) ?></em>
        </label>
      </div>
    <?php endforeach; ?>
    <button type="button" class="btn btn-primary mt-2" id="confirm-voucher">Xác nhận</button>
  <?php endif; ?>
</div>
</div>



      <div class="mb-3">
  <label class="form-label">
    <i class="fa fa-credit-card me-2"></i> Phương thức thanh toán
  </label>
  <div class="row text-center g-3 mb-3">
    <div class="col-3">
      <input type="radio" name="payment_method" id="momo" value="MOMO" hidden required>
      <label for="momo">
        <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" width="60" alt="Momo">
      </label>
    </div>
    <div class="col-3">
      <input type="radio" name="payment_method" id="banking" value="BANK" hidden>
      <label for="banking">
        <img src="https://cdn-icons-png.flaticon.com/512/633/633611.png" width="60" alt="Banking">
      </label>
    </div>
    <div class="col-3">
      <input type="radio" name="payment_method" id="cod" value="COD" hidden>
      <label for="cod">
        <img src="https://cdn-icons-png.flaticon.com/512/25/25694.png" width="60" alt="Ship COD">
      </label>
    </div>
    <div class="col-3">
      <input type="radio" name="payment_method" id="wallet" value="WALLET" hidden>
      <label for="wallet">
        <img src="https://cdn-icons-png.flaticon.com/512/2830/2830282.png" width="60" alt="Ví điện tử">
      </label>
    </div>
  </div>
</div>

      <div class="mb-3">
        <button type="submit" name="update_info" class="btn btn-secondary mb-2 w-100">Cập nhật thông tin</button>
        <button type="submit" formaction="create_order.php" class="btn btn-primary w-100">Tiếp tục thanh toán</button>
      </div>
    </form>
  </div>

  <div class="cart-right">
  <h5 class="fw-bold">Giỏ hàng</h5>
  <p>Có <strong><?= count($cart_items) ?></strong> sản phẩm trong giỏ hàng</p>
  <?php
    $total = 0;
    foreach ($cart_items as $item):
      $line_total = $item['price'] * $item['quantity'];
      $total += $line_total;
  ?>
    <div class="product-item">
  <div style="display: flex; gap: 10px;">
    <img src="<?= htmlspecialchars($item['thumbnail']) ?>" width="60" height="60" style="object-fit: cover; border: 1px solid #ccc;">
    <div>
      <div><strong><?= htmlspecialchars($item['title']) ?></strong> (x<?= $item['quantity'] ?>)</div>
      <div>Size: <?= htmlspecialchars($item['size']) ?> | Màu: <?= htmlspecialchars($item['color']) ?></div>
    </div>
  </div>
  <div><strong><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</strong></div>
</div>


  <?php endforeach; ?>
 <div class="product-item">
  <span>Phí giao hàng</span>
  <span><?= number_format($shipping, 0, ',', '.') ?>đ</span>
</div>

<?php if ($discount > 0): ?>
<div class="product-item text-success">
  <span>Giảm giá</span>
  <span>-<?= number_format($discount, 0, ',', '.') ?>đ</span>
</div>
<?php endif; ?>

<div class="product-item total">
  <span>Tổng cộng</span>
  <span><?= number_format($total_final, 0, ',', '.') ?>đ</span>
</div>

</div>

</div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const btnToggle = document.getElementById('btn-choose-voucher');
    const voucherList = document.getElementById('voucher-list');
    const confirmBtn = document.getElementById('confirm-voucher');
    const hiddenInput = document.getElementById('voucher-hidden');
    const selectedText = document.getElementById('selected-voucher');
    
    // Lấy số dư ví điện tử
    const walletBalance = <?= $user_info['balance'] ?>;
    const totalFinal = <?= $total_final ?>;

    // Thêm sự kiện cho các trường input
    const inputs = document.querySelectorAll('input[name="fullname"], input[name="address"], input[name="phone"]');
    inputs.forEach(input => {
      input.addEventListener('change', function() {
        const form = this.closest('form');
        const formData = new FormData(form);
        
        // Gửi form bằng AJAX để không làm mất các thông tin khác
        fetch(form.action, {
          method: 'POST',
          body: formData
        }).then(response => {
          if (response.ok) {
            // Hiển thị thông báo thành công
            const messageDiv = document.getElementById('update-message');
            messageDiv.style.display = 'block';
            setTimeout(() => {
              messageDiv.style.display = 'none';
            }, 3000); // Ẩn sau 3 giây
          }
        }).catch(error => {
          console.error('Lỗi khi cập nhật:', error);
        });
      });
    });

    // Kiểm tra số dư khi chọn thanh toán bằng ví điện tử
    const walletRadio = document.getElementById('wallet');
    walletRadio.addEventListener('change', function() {
      if (this.checked && walletBalance < totalFinal) {
        alert('Số dư ví điện tử không đủ để thanh toán!\nSố dư hiện tại: ' + 
              new Intl.NumberFormat('vi-VN').format(walletBalance) + 'đ\n' +
              'Số tiền cần thanh toán: ' + 
              new Intl.NumberFormat('vi-VN').format(totalFinal) + 'đ');
        this.checked = false;
        // Chọn COD làm mặc định
        document.getElementById('cod').checked = true;
      }
    });

    btnToggle.addEventListener('click', function () {
      voucherList.style.display = voucherList.style.display === 'none' ? 'block' : 'none';
    });
confirmBtn.addEventListener('click', function () {
  const checked = document.querySelector('input[name="voucher_select"]:checked');
  if (checked) {
    const voucher = checked.value;
    // Gửi lại trang để server tính toán giảm giá
    const fullname = document.querySelector('input[name="fullname"]').value;
const address = document.querySelector('input[name="address"]').value;
const phone = document.querySelector('input[name="phone"]').value;

const params = new URLSearchParams();
params.set('voucher', voucher);
params.set('fullname', fullname);
params.set('address', address);
params.set('phone', phone);

window.location.href = window.location.pathname + '?' + params.toString();

  } else {
    alert('Vui lòng chọn 1 mã giảm giá.');
  }
});

  });
</script>



<?php include('../../../footer.php'); ?>
</body>
</html>
