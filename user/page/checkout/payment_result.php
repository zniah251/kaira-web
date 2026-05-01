<?php
$status = $_GET['status'] ?? 'failed';
$orderId = $_GET['orderId'] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Kết quả thanh toán</title>
  <meta http-equiv="refresh" content="8; url=../../page/cart/cart.php"> <!-- Chuyển hướng sau 8 giây -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background: linear-gradient(to right, #e0f7fa, #fffde7);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .result-box {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      text-align: center;
      animation: fadeIn 0.5s ease;
    }
    .icon {
      font-size: 50px;
      margin-bottom: 20px;
    }
    .success { color: #4caf50; }
    .fail { color: #f44336; }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>
  <div class="result-box">
    <?php if ($status === 'success'): ?>
      <div class="icon success">✅</div>
      <h2>Thanh toán thành công!</h2>
      <p>Mã đơn hàng: <strong><?= htmlspecialchars($orderId) ?></strong></p>
      <p>Bạn sẽ được chuyển hướng về trang giỏ hàng trong giây lát...</p>
    <?php else: ?>
      <div class="icon fail">❌</div>
      <h2>Thanh toán thất bại hoặc bị huỷ!</h2>
      <p>Vui lòng thử lại hoặc liên hệ hỗ trợ.</p>
      <a href="../../page/cart/cart.php" class="btn btn-danger mt-3">Quay lại giỏ hàng</a>
    <?php endif; ?>
  </div>
</body>
</html>
