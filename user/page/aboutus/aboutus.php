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
            color: #000;
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
    </style>
</head>

<body>
    <?php include('../../../navbar.php'); ?>
    <!-------------------------------ABOUT US------------------------------------->
    <nav class="breadcrumb">
        <a href="../../index.html">Trang chủ</a> <span class="breadcrumb-separator">&gt;</span> <span>About us</span>
    </nav>
    <div class="container-abtus">
        <section class="abt-header">
            <h1>About KAIRA</h1>
            <p>Cập nhật ngày 14/04/2025</p>
            <img src="./img-abtus/background.png" alt="The KAIRA Team" class="team-photo">
        </section>
        <div class="tabs-wrapper">
            <div class="tabs-container">
                <div class="tab active">Giới thiệu <span>▴</span></div>
                <div class="tab"><a href="./chinhsach.html"> Các chính sách <span>▾</span> </a></div>
            </div>
        </div>


        <!-- Giới thiệu -->
        <section class="about-section">
            <div class="about-content">
                <div class="about-text">
                    <h2 style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"><span>A.</span> GIỚI THIỆU
                    </h2>
                    <p><strong class="highlight">KAIRA</strong> được thành lập vào năm 2025, KAIRA luôn hướng tới mục
                        tiêu mang đến cho khách hàng những sản phẩm thời trang đa dạng và có tính ứng dụng cao. Với
                        mong muốn được đồng hành lâu dài cùng khách hàng, KAIRA luôn cố gắng hoàn thiện chất
                        lượng sản phẩm và dịch vụ để khách hàng có những trải nghiệm tốt nhất khi mua sắm tại các
                        cửa hàng.</p>
                </div>
            </div>
        </section>
        <!--Giá trị cốt lõi-->
        <section class="core-values-section">
            <div class="core-values-container">
                <div class="core-values-text">
                    <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">1. Giá trị cốt lõi</h2>
                    <ul>
                        <li><strong>Chất lượng – Trải nghiệm – Chuyên nghiệp – Tận tâm – Trách nhiệm</strong></li>
                        <li>Chất lượng không đơn thuần là một điểm đến mà là một hành trình.</li>
                        <li>Trải nghiệm là chìa khoá kết nối.</li>
                        <li>Chuyên nghiệp không chỉ là sản phẩm của kiến thức, kinh nghiệm mà còn là kết quả của sự nỗ
                            lực, tâm huyết và đam mê.</li>
                        <li>Tận tâm trong mọi suy nghĩ và hành động.</li>
                        <li>Trách nhiệm – nền tảng của sự tin cậy và thành công.</li>
                    </ul>
                </div>
                <div class="core-values-images">
                    <img src="./img-abtus/value_1.png" alt="Team" class="circle-img img1">
                    <img src="./img-abtus/value_2.png" alt="Staff" class="circle-img img2">
                    <img src="./img-abtus/value_3.png" alt="Work" class="circle-img img3">
                </div>
            </div>
        </section>
        <!-- Section 2: Sứ mệnh -->
        <section class="mission-section">
            <div class="mission-container">
                <div class="mission-image">
                    <img src="./img-abtus/value_4.png" alt="Nhân viên" class="circle-img">
                </div>
                <div class="mission-content">
                    <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">2. Sứ mệnh</h2>
                    <p>
                        Chúng tôi tự hào mang đến cho khách hàng những sản phẩm thời trang hiện đại, ứng dụng và
                        trải nghiệm mua sắm tốt nhất.
                    </p>
                </div>
            </div>
        </section>

        <!-- Section 3: Tầm nhìn -->
        <section class="vision-section">
            <div class="vision-container">
                <div class="vision-text">
                    <h2 style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">3. Tầm nhìn</h2>
                    <p>Trở thành một trong những thương hiệu dẫn đầu trong lĩnh vực thời trang ứng dụng tại Việt Nam.
                    </p>
                </div>
                <div class="vision-image">
                    <img src="./img-abtus/value_5.png" alt="Cửa hàng" class="circle-img">
                </div>
            </div>
        </section>
    </div>

     <?php include('../../../footer.php'); ?>


    <script src="js/jquery.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/SmoothScroll.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="js/script.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.js"></script>

</body>