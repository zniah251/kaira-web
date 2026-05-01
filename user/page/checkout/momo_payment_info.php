<?php
session_start(); // Bắt đầu session ở đầu file
// Kiểm tra xem người dùng đã đăng nhập chưa
// Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    // Điều chỉnh đường dẫn đến trang đăng nhập của bạn
    header("Location: /e-web/user/page/sign-in/login2.php");
    exit();
}
$orderId = $_GET['orderId'] ?? 'N/A';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kaira - Chuyển khoản MOMO</title>
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
      font-family: 'Times New Roman', serif;
      background-color: #fff;
      color: #000;
    }

    .container-custom {
      max-width: 600px;
      margin: 60px auto;
      background-color: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      text-align: center;
    }

    h2 {
      font-family: 'Times New Roman', serif;
      font-weight: bold;
      margin-bottom: 25px;
      color: #000;
    }

    p {
      font-size: 17px;
      margin-bottom: 12px;
    }

    .note {
      background-color: #fdf6e3;
      padding: 10px 15px;
      font-weight: bold;
      font-size: 18px;
      color: #b71c1c;
      border-radius: 10px;
      display: inline-block;
      margin-top: 10px;
    }

    .btn-confirm {
      margin-top: 30px;
      padding: 12px 24px;
      font-size: 16px;
      font-weight: bold;
      background-color: #000;
      color: #fff;
      border-radius: 30px;
      border: none;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .btn-confirm:hover {
      background-color: #333;
    }

    .momo-logo {
      width: 280px;
      margin-bottom: 20px;
      border-radius: 8px;
    }
  </style>
</head>
<body>
<?php include('../../../navbar.php'); ?>

<div class="container-custom">

  <img src="../../../blog/momo.jpg" alt="Momo" class="momo-logo">
  
  <p><i class="fas fa-user me-2 text-dark"></i><strong>Chủ tài khoản:</strong> Trương Huy Hoàng</p>
  <p><strong>Số điện thoại:</strong> 0328 243 239</p>

  <p><strong>Nội dung chuyển khoản:</strong><br>
    <span class="note">THANH_TOAN_ĐON_HANG_SO <?= htmlspecialchars($orderId) ?></span>
  </p>

  <p class="text-muted mt-3">💬 Kaira sẽ tiến hành giao hàng sau khi nhận thanh toán thành công. Xin cảm ơn quý khách!</p>

  <a href="../../index.php" class="btn btn-confirm">Xác nhận đã thanh toán</a>
</div>

<?php include('../../../footer.php'); ?>
</body>
</html>
