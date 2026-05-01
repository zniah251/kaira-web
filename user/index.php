<?php
session_start();
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start(); // Dòng này cần phải ở đầu file PHP, trước mọi output khác.
}
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_voucher') {

  if (!isset($_SESSION['uid'])) {
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
    exit;
  }
  $uid = $_SESSION['uid'];
  $vid = intval($_POST['vid']);
  $check = $conn->query("SELECT * FROM user_voucher WHERE uid = $uid AND vid = $vid");
  if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Bạn đã lưu voucher này rồi!']);
    exit;
  }
  $sql = "INSERT INTO user_voucher (uid, vid) VALUES ($uid, $vid)";
  if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Lưu voucher thành công!']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu voucher!']);
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Kaira</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="author" content="TemplatesJungle">
  <meta name="keywords" content="ecommerce,fashion,store">
  <meta name="description" content="Bootstrap 5 Fashion Store HTML CSS Template">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/vendor.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap"
    rel="stylesheet">
  <style>
    .dropdown-submenu {
      position: relative;
    }

    .dropdown-submenu .dropdown-menu {
      top: 0;
      left: 100%;
      margin-top: -1px;
    }

    .dropdown-submenu:hover .dropdown-menu {
      display: block;
    }

    body,
    footer {
      font-family: 'Times New Roman', Times, serif;
    }


    .line-clamp-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .section-title,
    .navbar,
    .col-md-6 {
      font-family: 'Times New Roman', Times, serif;
    }

    body,
    header,
    footer {
      font-family: 'Times New Roman', Times, serif;
    }

    .image-holder {
      width: 100%;
      aspect-ratio: 3 / 4;
      /* Hoặc 4/5, tùy phong cách bạn muốn */
      overflow: hidden;
      position: relative;
    }

    .image-holder img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
      /* Hiệu ứng zoom giữ nguyên */
    }

    .product-image {
      width: 100%;
      aspect-ratio: 5 / 12;
      /* Hoặc 4/5, tùy phong cách bạn muốn */
      overflow: hidden;
      position: relative;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* Hiệu ứng zoom giữ nguyên */
    }
  </style>
</head>

<body class="homepage">

  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <defs>
      <symbol xmlns="http://www.w3.org/2000/svg" id="instagram" viewBox="0 0 15 15">
        <path fill="none" stroke="currentColor"
          d="M11 3.5h1M4.5.5h6a4 4 0 0 1 4 4v6a4 4 0 0 1-4 4h-6a4 4 0 0 1-4-4v-6a4 4 0 0 1 4-4Zm3 10a3 3 0 1 1 0-6a3 3 0 0 1 0 6Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="facebook" viewBox="0 0 15 15">
        <path fill="none" stroke="currentColor"
          d="M7.5 14.5a7 7 0 1 1 0-14a7 7 0 0 1 0 14Zm0 0v-8a2 2 0 0 1 2-2h.5m-5 4h5" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="twitter" viewBox="0 0 15 15">
        <path fill="currentColor"
          d="m14.478 1.5l.5-.033a.5.5 0 0 0-.871-.301l.371.334Zm-.498 2.959a.5.5 0 1 0-1 0h1Zm-6.49.082h-.5h.5Zm0 .959h.5h-.5Zm-6.99 7V12a.5.5 0 0 0-.278.916L.5 12.5Zm.998-11l.469-.175a.5.5 0 0 0-.916-.048l.447.223Zm3.994 9l.354.353a.5.5 0 0 0-.195-.827l-.159.474Zm7.224-8.027l-.37.336l.18.199l.265-.04l-.075-.495Zm1.264-.94c.051.778.003 1.25-.123 1.606c-.122.345-.336.629-.723 1l.692.722c.438-.42.776-.832.974-1.388c.193-.546.232-1.178.177-2.006l-.998.066Zm0 3.654V4.46h-1v.728h1Zm-6.99-.646V5.5h1v-.959h-1Zm0 .959V6h1v-.5h-1ZM10.525 1a3.539 3.539 0 0 0-3.537 3.541h1A2.539 2.539 0 0 1 10.526 2V1Zm2.454 4.187C12.98 9.503 9.487 13 5.18 13v1c4.86 0 8.8-3.946 8.8-8.813h-1ZM1.03 1.675C1.574 3.127 3.614 6 7.49 6V5C4.174 5 2.421 2.54 1.966 1.325l-.937.35Zm.021-.398C.004 3.373-.157 5.407.604 7.139c.759 1.727 2.392 3.055 4.73 3.835l.317-.948c-2.155-.72-3.518-1.892-4.132-3.29c-.612-1.393-.523-3.11.427-5.013l-.895-.446Zm4.087 8.87C4.536 10.75 2.726 12 .5 12v1c2.566 0 4.617-1.416 5.346-2.147l-.708-.706Zm7.949-8.009A3.445 3.445 0 0 0 10.526 1v1c.721 0 1.37.311 1.82.809l.74-.671Zm-.296.83a3.513 3.513 0 0 0 2.06-1.134l-.744-.668a2.514 2.514 0 0 1-1.466.813l.15.989ZM.222 12.916C1.863 14.01 3.583 14 5.18 14v-1c-1.63 0-3.048-.011-4.402-.916l-.556.832Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="pinterest" viewBox="0 0 15 15">
        <path fill="none" stroke="currentColor"
          d="m4.5 13.5l3-7m-3.236 3a2.989 2.989 0 0 1-.764-2V7A3.5 3.5 0 0 1 7 3.5h1A3.5 3.5 0 0 1 11.5 7v.5a3 3 0 0 1-3 3a2.081 2.081 0 0 1-1.974-1.423L6.5 9m1 5.5a7 7 0 1 1 0-14a7 7 0 0 1 0 14Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="youtube" viewBox="0 0 15 15">
        <path fill="currentColor"
          d="m1.61 12.738l-.104.489l.105-.489Zm11.78 0l.104.489l-.105-.489Zm0-10.476l.104-.489l-.105.489Zm-11.78 0l.106.489l-.105-.489ZM6.5 5.5l.277-.416A.5.5 0 0 0 6 5.5h.5Zm0 4H6a.5.5 0 0 0 .777.416L6.5 9.5Zm3-2l.277.416a.5.5 0 0 0 0-.832L9.5 7.5ZM0 3.636v7.728h1V3.636H0Zm15 7.728V3.636h-1v7.728h1ZM1.506 13.227c3.951.847 8.037.847 11.988 0l-.21-.978a27.605 27.605 0 0 1-11.568 0l-.21.978ZM13.494 1.773a28.606 28.606 0 0 0-11.988 0l.21.978a27.607 27.607 0 0 1 11.568 0l.21-.978ZM15 3.636c0-.898-.628-1.675-1.506-1.863l-.21.978c.418.09.716.458.716.885h1Zm-1 7.728a.905.905 0 0 1-.716.885l.21.978A1.905 1.905 0 0 0 15 11.364h-1Zm-14 0c0 .898.628 1.675 1.506 1.863l.21-.978A.905.905 0 0 1 1 11.364H0Zm1-7.728c0-.427.298-.796.716-.885l-.21-.978A1.905 1.905 0 0 0 0 3.636h1ZM6 5.5v4h1v-4H6Zm.777 4.416l3-2l-.554-.832l-3 2l.554.832Zm3-2.832l-3-2l-.554.832l3 2l.554-.832Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="category" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M19 5.5h-6.28l-.32-1a3 3 0 0 0-2.84-2H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-10a3 3 0 0 0-3-3Zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1h4.56a1 1 0 0 1 .95.68l.54 1.64a1 1 0 0 0 .95.68h7a1 1 0 0 1 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="calendar" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="heart" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="plus" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="minus" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 11H5a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="check" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M18.71 7.21a1 1 0 0 0-1.42 0l-7.45 7.46l-3.13-3.14A1 1 0 1 0 5.29 13l3.84 3.84a1 1 0 0 0 1.42 0l8.16-8.16a1 1 0 0 0 0-1.47Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="trash" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M10 18a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1ZM20 6h-4V5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2ZM10 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm7 14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-7h16Zm-3-1a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="star-outline" viewBox="0 0 15 15">
        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
          d="M7.5 9.804L5.337 11l.413-2.533L4 6.674l2.418-.37L7.5 4l1.082 2.304l2.418.37l-1.75 1.793L9.663 11L7.5 9.804Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="star-solid" viewBox="0 0 15 15">
        <path fill="currentColor"
          d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="search" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24">
        <path fill="currentColor"
          d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 15 15">
        <path fill="currentColor"
          d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
      </symbol>
    </defs>
  </svg>

  <div class="preloader text-white fs-6 text-uppercase overflow-hidden"></div>

  <div class="search-popup">
    <div class="search-popup-container">
      <form role="search" method="get" class="form-group" action="/e-web/user/page/ajax_handlers/search_results.php">
        <input type="search" id="search-form" class="form-control border-0 border-bottom"
          placeholder="Type and press enter" value="" name="s" />
        <button type="submit" class="search-submit border-0 position-absolute bg-white"
          style="top: 15px;right: 15px;"></button>
      </form>
    </div>
  </div>
  <?php include('../navbar.php'); ?>
  <div class="banner-container">
    <div class="banner-wrapper">
      <img src="images/banner.png" alt="banner" class="banner-image">
      <div class="banner-content">
        <!--<a href="#collection" class="banner-cta">Xem Bộ Sưu Tập</a>-->
      </div>
    </div>
    <div class="swiper-pagination"></div>
  </div>

  <section class="voucher-container">
    <div class="voucher" id="vouchership">
      <div class="voucher-left">
        <h3 class="voucher-title">FREESHIP</h3>
        <p class="voucher-condition">Đơn hàng từ 300k</p>
        <p class="voucher-detail">Giảm giá không giới hạn</p>
        <p class="voucher-expire"><em>HSD đến: 31-12-2025</em></p>
      </div>
      <div class="voucher-right">
        <i class="fa-solid fa-truck-fast"></i>
        <button class="voucher-btn" id="vcship">Lưu ngay</button>
      </div>
    </div>

    <div class="voucher" id="voucherdiscount">
      <div class="voucher-left">
        <h3 class="voucher-title">DISCOUNT</h3>
        <p class="voucher-condition">Đơn hàng từ 300k</p>
        <p class="voucher-detail">Giảm 10% tổng giá trị đơn hàng</p>
        <p class="voucher-expire"><em>HSD đến: 31-12-2025</em></p>
      </div>
      <div class="voucher-right">
        <i class="fa-solid fa-money-bill"></i>
        <button class="voucher-btn" id="vcdc">Lưu ngay</button>
      </div>
    </div>
  </section>

  <section id="billboard" class="bg-light py-5">
    <div class="container">
      <div class="row justify-content-center">
        <h1 class="section-title text-center mt-4" data-aos="fade-up">New Collections</h1>
        <div class="col-md-6 text-center" data-aos="fade-up" data-aos-delay="300">
          <p>Khám phá bộ sưu tập váy mới gồm 10 thiết kế độc đáo, lấy cảm hứng từ phong cách học đường hiện đại kết hợp nét cổ điển tinh tế. Từ những chiếc váy dáng xòe đáng yêu, cổ áo cách điệu đến hoạ tiết kẻ caro thời thượng – mỗi mẫu váy là một tuyên ngôn phong cách dành cho những cô nàng yêu sự thanh lịch nhưng không kém phần năng động.</p>
        </div>
      </div>
      <div class="row">
        <div class="swiper main-swiper py-4" data-aos="fade-up" data-aos-delay="600">
          <div class="swiper-wrapper d-flex border-animation-left">
            <?php
            $str = "SELECT * FROM product WHERE pid > 100 LIMIT 10";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo "<div class='swiper-slide'>";
              echo "  <div class='banner-item image-zoom-effect'>";
              echo "    <div class='image-holder'>";
              echo "      <a href='#'>";
              echo "        <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='product' class='img-fluid' id='" . $row['pid'] . "'>";
              echo "      </a>";
              echo "    </div>";
              echo "    <div class='banner-content py-4'>";
              echo "      <h5 class='element-title text-uppercase' style='font-family: \'Times New Roman'\ Times, serif';";
              echo "        <a href='index.html' class='item-anchor'>" . $row["title"] . "</a>";
              echo "      </h5>";
              echo "      <p class='line-clamp-2'>" . $row["description"] . "</p>";
              echo "      <div class='btn-left'>";
              echo "        <a href='#' class='btn-link fs-6 text-uppercase item-anchor text-decoration-none' style='font-family: 'Times New Roman' Times, serif';>Discover Now</a>";
              echo "      </div>";
              echo "    </div>";
              echo "  </div>";
              echo "</div>";
            }
            ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </section>

  <section id="new-arrival" class="new-arrival product-carousel py-5 position-relative overflow-hidden">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-center mt-5 mb-3">
        <h4 class="text-uppercase" style="font-family: 'Times New Roman', Times, serif;">Our New Arrivals</h4>
        <a href="index.html" class="btn-link">View All Products</a>
      </div>
      <div class="swiper product-swiper open-up" data-aos="zoom-out">
        <div class="swiper-wrapper d-flex">
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 10";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 12";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 14";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 16";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 18";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
      <div class="icon-arrow icon-arrow-left"><svg width="30" height="30" viewBox="0 0 24 24">
          <use xlink:href="#arrow-left"></use>
        </svg></div>
      <div class="icon-arrow icon-arrow-right"><svg width="30" height="30" viewBox="0 0 24 24">
          <use xlink:href="#arrow-right"></use>
        </svg></div>
    </div>
  </section>


  <section id="best-sellers" class="best-sellers product-carousel py-5 position-relative overflow-hidden">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-center mt-5 mb-3">
        <h4 class="text-uppercase" style="font-family:'Times New Roman', Times, serif;">Best Selling Items</h4>
        <a href="index.html" class="btn-link">View All Products</a>
      </div>
      <div class="swiper product-swiper open-up" data-aos="zoom-out">
        <div class="swiper-wrapper d-flex">
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 1";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 8";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 133";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 32";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
          <div class="swiper-slide">
            <?php
            $str = "SELECT * FROM product WHERE pid = 123";
            $query = $conn->query($str);
            while ($row = $query->fetch_assoc()) {
              echo '
              <div class="product-item image-zoom-effect link-effect">
              <div class="image-holder position-relative">
                <a href="index.html">';
              echo "      <img src='../admin/assets/images/" . $row["thumbnail"] . "' alt='categories' class='product-image img-fluid' id='" . $row['pid'] . "'>";

              echo '
                </a>
                <a href="index.html" class="btn-icon btn-wishlist">
                  <svg width="24" height="24" viewBox="0 0 24 24">
                    <use xlink:href="#heart"></use>
                  </svg>
                </a>
              </div>
            </div>
            ';
            }
            ?>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
      <div class="icon-arrow icon-arrow-left"><svg width="30" height="30" viewBox="0 0 24 24">
          <use xlink:href="#arrow-left"></use>
        </svg></div>
      <div class="icon-arrow icon-arrow-right"><svg width="30" height="30" viewBox="0 0 24 24">
          <use xlink:href="#arrow-right"></use>
        </svg></div>
    </div>
  </section>

  <section class="video py-5 overflow-hidden">
    <div class="container-fluid">
      <div class="row">
        <div class="video-content open-up" data-aos="zoom-out">
          <div class="video-bg">
            <img src="images/video-image.jpg" alt="video" class="video-image img-fluid">
          </div>
          <div class="video-player">
            <a class="youtube" href="https://www.youtube.com/embed/pjtsGzQjFM4">
              <svg width="24" height="24" viewBox="0 0 24 24">
                <use xlink:href="#play"></use>
              </svg>
              <img src="images/text-pattern.png" alt="pattern" class="text-rotate">
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="blog py-5">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-center mt-5 mb-3">
        <h4 class="text-uppercase">Read Blog Posts</h4>
        <a href="/e-web/user/page/blog/blog.php" class="btn-link">View All</a>
      </div>
      <div class="row">
        <div class="col-md-4">
          <article class="post-item">
            <div class="post-image">
              <a href="index.html">
                <img src="images/post-image1.jpg" alt="image" class="post-grid-image img-fluid">
              </a>
            </div>
            <div class="post-content d-flex flex-wrap gap-2 my-3">
              <div class="post-meta text-uppercase fs-6 text-secondary">
                <span class="post-category">Fashion /</span>
                <span class="meta-day"> jul 11, 2022</span>
              </div>
              <h5 class="post-title text-uppercase">
                <a href="index.html">How to look outstanding in pastel</a>
              </h5>
              <p>Dignissim lacus,turpis ut suspendisse vel tellus.Turpis purus,gravida orci,fringilla...</p>
            </div>
          </article>
        </div>
        <div class="col-md-4">
          <article class="post-item">
            <div class="post-image">
              <a href="index.html">
                <img src="images/post-image2.jpg" alt="image" class="post-grid-image img-fluid">
              </a>
            </div>
            <div class="post-content d-flex flex-wrap gap-2 my-3">
              <div class="post-meta text-uppercase fs-6 text-secondary">
                <span class="post-category">Fashion /</span>
                <span class="meta-day"> jul 11, 2022</span>
              </div>
              <h5 class="post-title text-uppercase">
                <a href="index.html">Top 10 fashion trend for summer</a>
              </h5>
              <p>Turpis purus, gravida orci, fringilla dignissim lacus, turpis ut suspendisse vel tellus...</p>
            </div>
          </article>
        </div>
        <div class="col-md-4">
          <article class="post-item">
            <div class="post-image">
              <a href="index.html">
                <img src="images/post-image3.jpg" alt="image" class="post-grid-image img-fluid">
              </a>
            </div>
            <div class="post-content d-flex flex-wrap gap-2 my-3">
              <div class="post-meta text-uppercase fs-6 text-secondary">
                <span class="post-category">Fashion /</span>
                <span class="meta-day"> jul 11, 2022</span>
              </div>
              <h5 class="post-title text-uppercase">
                <a href="index.html">Crazy fashion with unique moment</a>
              </h5>
              <p>Turpis purus, gravida orci, fringilla dignissim lacus, turpis ut suspendisse vel tellus...</p>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>

  <section class="logo-bar py-5 my-5">
    <div class="container">
      <div class="row">
        <div class="logo-content d-flex flex-wrap justify-content-between">
          <img src="images/logo1.png" alt="logo" class="logo-image img-fluid">
          <img src="images/logo2.png" alt="logo" class="logo-image img-fluid">
          <img src="images/logo3.png" alt="logo" class="logo-image img-fluid">
          <img src="images/logo4.png" alt="logo" class="logo-image img-fluid">
          <img src="images/logo5.png" alt="logo" class="logo-image img-fluid">
        </div>
      </div>
    </div>
  </section>

  <section class="instagram position-relative">
    <div class="d-flex justify-content-center w-100 position-absolute bottom-0 z-1">
      <a href="https://www.instagram.com/templatesjungle/" class="btn btn-dark px-5">Follow us on Instagram</a>
    </div>
    <div class="row g-0">
      <div class="col-6 col-sm-4 col-md-2">
        <div class="insta-item">
          <a href="https://www.instagram.com/templatesjungle/" target="_blank">
            <img src="images/insta-item1.jpg" alt="instagram" class="insta-image img-fluid">
          </a>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-2">
        <div class="insta-item">
          <a href="https://www.instagram.com/templatesjungle/" target="_blank">
            <img src="images/insta-item2.jpg" alt="instagram" class="insta-image img-fluid">
          </a>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-2">
        <div class="insta-item">
          <a href="https://www.instagram.com/templatesjungle/" target="_blank">
            <img src="images/insta-item3.jpg" alt="instagram" class="insta-image img-fluid">
          </a>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-2">
        <div class="insta-item">
          <a href="https://www.instagram.com/templatesjungle/" target="_blank">
            <img src="images/insta-item4.jpg" alt="instagram" class="insta-image img-fluid">
          </a>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-2">
        <div class="insta-item">
          <a href="https://www.instagram.com/templatesjungle/" target="_blank">
            <img src="images/insta-item5.jpg" alt="instagram" class="insta-image img-fluid">
          </a>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-2">
        <div class="insta-item">
          <a href="https://www.instagram.com/templatesjungle/" target="_blank">
            <img src="images/insta-item6.jpg" alt="instagram" class="insta-image img-fluid">
          </a>
        </div>
      </div>
    </div>
  </section>

  <footer id="footer" class="footer-custom mt-5">
    <?php include('../footer.php'); ?>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/plugins.js"></script>
  <script src="js/SmoothScroll.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
  <script src="js/script.min.js"></script>

  <script>
    document.querySelectorAll('.product-image, .img-fluid').forEach(function(img) {
      img.addEventListener('click', function(event) {
        event.preventDefault(); // Chặn chuyển trang mặc định của thẻ <a>
        var proid = this.getAttribute('id');
        window.location.href = 'page/product_detail/product_detail.php?pid=' + proid;
      });
    });

    var isLoggedIn = <?php echo isset($_SESSION['uid']) ? 'true' : 'false'; ?>;
    document.querySelectorAll('.voucher-btn').forEach(function(button) {
      button.addEventListener('click', function(event) {
        event.preventDefault(); // Chặn hành động mặc định của nút
        if (!isLoggedIn) {
          alert('Vui lòng đăng nhập để sử dụng voucher này.');
          window.location.href = 'page/sign-in/login2.php';
        } else {
          var vID = this.getAttribute('id');
          if (vID === 'vcship') {
            fetch('', {
                method: 'POST', // sửa 'methor' thành 'method'
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=save_voucher&vid=1'
              })
              .then(response => response.json()) // sửa 'respone' thành 'response'
              .then(data => {
                alert(data.message);
              })
              .catch(() => {
                alert('Lỗi khi lưu voucher. Vui lòng thử lại sau.');
              });
          } else if (vID === 'vcdc') {
            fetch('', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'action=save_voucher&vid=2'
              })
              .then(response => response.json())
              .then(data => {
                alert(data.message);
              })
              .catch(() => {
                alert('Lỗi khi lưu voucher. Vui lòng thử lại sau.');
              });
          }
        }
      });
    });
  </script>

</body>


</html>