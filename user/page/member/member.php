<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kaira</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../../user/css/vendor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="../../css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../css/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="../../index.html">
    <link rel="stylesheet" type="text/css" href="../aboutus/abtus.css">
    <link rel="stylesheet" type="text/css" href="../faq/faqstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: 'Times New Roman', Times, serif !important;
            /* Sử dụng font Times New Roman cho tiêu đề */
        }
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

        .note-box {
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: auto;
            color: #000;
        }

        .note-box p {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .note-box ul {
            list-style-type: none;
            padding-left: 0;
        }

        .note-box li::before {
            content: "- ";
        }
    </style>
</head>

<body>
   <?php include('../../../navbar.php'); ?>
    <!-------------------------------MEMBERSHIP------------------------------------->
    <nav class="breadcrumb">
        <a href="../../index.html">Trang chủ</a> <span class="breadcrumb-separator">&gt;</span> <a
            href="./member.html">Membership</a>
    </nav>
    <div class="container-abtus">
        <section class="abt-header">
            <h1>Chính sách Membership </h1>
            <img src="./img-mem/membership.png" alt="The KAIRA Team" class="team-photo">
        </section>
    </div>
    <div class="note-box">
        <p><strong>Lưu ý:</strong></p>
        <ul>
            <li>Ưu đãi chỉ áp dụng khi khách xuất trình thẻ VIP/Premium lúc thanh toán và không áp dụng chung cho
                các chương trình khuyến mãi khác.</li>
            <li>Ưu đãi sinh nhật chỉ áp dụng 1 lần trong tháng sinh nhật.</li>
            <li>Điểm tích lũy sẽ được giữ nguyên trong suốt thời gian khách hàng tham gia membership. Với điều kiện
                thành viên cần phát sinh giao dịch trong vòng 12 tháng.</li>
        </ul>
    </div>
    <!--footer-->
     <?php include('../../../footer.php'); ?>
    <script src="js/jquery.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/SmoothScroll.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="js/script.min.js"></script>
</body>