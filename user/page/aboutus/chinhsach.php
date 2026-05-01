<!DOCTYPE html>
<html lang="en">
<?php
// connect.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-web";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<head>
    <title>Kaira</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bootstrap 5 Fashion Store HTML CSS Template">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../../user/css/vendor.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="../../css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../css/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="../../index.html">
    <link rel="stylesheet" type="text/css" href="./abtus.css">
    <link rel="stylesheet" type="text/css" href="../faq/faqstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Times New Roman', Times, serif !important;
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
    <!-------------------------------CHÍNH SÁCH------------------------------------->
    <nav class="breadcrumb">
        <a href="../../index.html">Trang chủ</a> <span class="breadcrumb-separator">&gt;</span> <a
            href="./aboutus.html">About us</a>
    </nav>
    <div class="container-abtus">
        <section class="abt-header">
            <h1>About KAIRA</h1>
            <p>Cập nhật ngày 14/04/2025</p>
        </section>
        <div class="tabs-wrapper">
            <div class="tabs-container">
                <div class="tab active"><a href="./aboutus.html"> Giới thiệu <span>▴</span></a></div>
                <div class="tab"><a href="./chinhsach.html"> Các chính sách <span>▾</span> </a></div>
            </div>
        </div>
    </div>
    <section class="faq-container">
        <h2>B. CÁC CHÍNH SÁCH</h2>

        <div class="faq-item">
            <p>KAIRA được thành lập vào năm 2025, KAIRA luôn hướng tới mục tiêu mang đến cho khách hàng những sản
                phẩm thời trang đa dạng và có tính ứng dụng cao. Với mong muốn được đồng hành lâu dài cùng khách hàng,
                KAIRA luôn cố gắng hoàn thiện chất lượng sản phẩm và dịch vụ để khách hàng có những trải nghiệm tốt
                nhất khi mua sắm tại các cửa hàng.</p>
        </div>

        <div class="faq-item">
            <h3>1. Chính sách bảo hành và đổi sản phẩm</h3>
            <p>Các sản phẩm khi đặt hàng trên website của shop sẽ được gửi đi thông qua đơn vị vận chuyển giao hàng tiết
                kiệm.</p>
        </div>

        <div class="faq-item">
            <h3>2. Chính sách bảo mật</h3>
            <p>
                Quyền riêng tư của khách hàng vô cùng quan trọng với KAIRA, vì thế chúng tôi chỉ sẽ sử dụng tên và một
                số thông tin khác của quý khách theo cách được đề ra trong Chính sách Bảo mật này. Chúng tôi chỉ sẽ thu
                thập những thông tin cần thiết và có liên quan đến giao dịch giữa chúng tôi và quý khách.
            </p>
            <p>
                Chúng tôi chỉ giữ thông tin của quý khách trong thời gian luật pháp yêu cầu hoặc cho mục đích mà thông
                tin đó được thu thập. Quý khách có thể ghé thăm trang web mà không cần phải cung cấp bất kỳ thông tin cá
                nhân nào. Khi viếng thăm trang web, quý khách sẽ luôn ở trong tình trạng vô danh.
            </p>
            <p><strong>Quy định bảo mật của KAIRA hoàn toàn tuân theo Nghị định 52/2013/NĐ-CP.</strong></p>

            <h3>Thu thập thông tin cá nhân</h3>
            <p>
                KAIRA cam kết không bán, chia sẻ hay trao đổi thông tin cá nhân của khách hàng thu thập trên trang web
                cho một bên thứ ba nào khác.<br>
                Thông tin cá nhân thu thập được sẽ chỉ được sử dụng trong nội bộ công ty.<br>
                Các thông tin KAIRA sẽ thu thập khi khách hàng có ý định mua hàng gồm:
            </p>
            <ul>
                <li><strong>Email</strong></li>
                <li><strong>Số điện thoại</strong></li>
                <li><strong>Địa chỉ</strong></li>
            </ul>

            <p>Những thông tin trên sẽ được sử dụng cho một hoặc tất cả các mục đích sau đây:</p>
            <ul>
                <li>Thông báo thông tin đơn hàng và kết quả đặt hàng của quý khách.</li>
                <li>Thông báo các chương trình khuyến mãi dành cho khách hàng</li>
                <li>Xử lý các vấn đề bất thường có thể xảy ra khi quý khách mua sắm online</li>
                <li>Phản hồi về những đề nghị khiến khách hàng không hài lòng khi mua sắm.</li>
                <li>Giải đáp các thắc mắc của quý khách.</li>
            </ul>

            <p>
                Ngoài ra, chúng tôi sẽ sử dụng thông tin quý khách cung cấp để xác nhận và thực hiện các giao dịch tài
                chính liên quan đến các khoản thanh toán trực tuyến của quý khách; kiểm tra dữ liệu tải từ trang web của
                chúng tôi; cải thiện giao diện và/hoặc nội dung của các trang mục trên trang web và tùy chỉnh để dễ dàng
                hơn khi sử dụng; nhận diện khách viếng thăm trang web; nghiên cứu nhân khẩu học người sử dụng; gửi đến
                quý khách thông tin mà chúng tôi nghĩ sẽ có ích hoặc quý khách yêu cầu, bao gồm thông tin về sản phẩm và
                các dịch vụ.
            </p>

        </div>

        <div class="faq-item">
            <h3>3. Chính sách kiểm hàng</h3>
            <p>
            <p>
                Khách hàng được quyền kiểm tra hàng khi nhận từ bên giao hàng. Nếu có tình trạng hư hỏng, không còn
                nguyên vẹn, ... quý khách có thể hoàn trả cho bên giao hàng để được đổi sản phẩm mới.
            </p>
            <p>
                Đối với những vấn đề phát sinh do bên giao hàng gây ra làm ảnh hưởng đến quyền lợi của khách hàng,
                THECIU sẽ xử lý và nhận trách nhiệm bồi thường cho quý khách.
            </p>
            <p>Các thông tin về hoàn trả nêu như sản phẩm hỏng hóc:</p>

            <h4>• THỜI HẠN HOÀN TRẢ</h4>
            <p><strong>01-03 ngày</strong> hoặc ngay lúc nhận hàng</p>

            <h4>• ĐIỀU KIỆN HOÀN TRẢ</h4>
            <p>Sản phẩm phải đáp ứng tất cả các điều kiện sau đây mới có thể tiến hành đổi trả:</p>
            <ul>
                <li>Còn trong thời hạn đổi</li>
                <li>Sản phẩm trong thời hạn hoàn trả</li>
                <li>Sản phẩm đổi/trả phải còn nguyên vẹn, không bị rách, móp, bể vỡ...</li>
                <li>Sản phẩm còn nguyên tem, mác của nhà sản xuất.</li>
            </ul>

            <h4>• PHƯƠNG THỨC HOÀN TRẢ</h4>
            <p><strong>Bước 1:</strong> Sau khi nhận được hàng hoặc trước khi mua hàng tại cửa hàng, quý khách kiểm tra
                kỹ 1 lần trước khi nhận hàng. Nếu có vấn đề xin vui lòng liên hệ <strong>0775.865.912</strong> (với
                trường hợp mua hàng online) hoặc nhân viên cửa hàng (với trường hợp mua hàng trực tiếp).</p>

            <p><strong>Bước 2:</strong> Nếu mua hàng trực tiếp, quý khách được hướng dẫn và hỗ trợ đổi kiểm hàng và trả
                hàng tại chỗ. Với trường hợp mua hàng online, chúng tôi sẽ thỏa thuận với khách hàng về thời gian và
                cách thức đổi trả, hoàn tiền sản phẩm.</p>

            <p><strong>Bước 3:</strong> Sau khi nhận lại được sản phẩm ban đầu từ khách hàng, chúng tôi sẽ tiến hành vận
                chuyển sản phẩm thay thế đến đơn hàng hoặc hoàn tiền theo yêu cầu của khách hàng. Số tiền hoàn trả sẽ
                phải trừ đi chi phí phát sinh theo từng đơn cụ thể.</p>

            <h4>• NHỮNG TRƯỜNG HỢP KHÁCH HÀNG ĐƯỢC ĐỔI TRẢ VÀ HOÀN TIỀN SẢN PHẨM MÀ KHÔNG PHẢI CHỊU CHI PHÍ PHÁT SINH
            </h4>
            <ul>
                <li>Sản phẩm không đúng bóc, không đủ số lượng theo thỏa thuận</li>
                <li>Sản phẩm không đúng thiết kế hoặc mô tả ban đầu: rách bao bì, rớt chi, sút chi, …</li>
                <li>Sản phẩm giao không đúng theo đơn đặt hàng</li>
            </ul>
            </p>
        </div>


        </div>
    </section>
    <!--footer-->
        <footer id="footer" class="footer-custom mt-5">
            <div class="container">
                <div class="row justify-content-between py-5">

                    <!-- Logo & mô tả -->
                    <div class="col-md-3 col-sm-6">
                        <h4 class="fw-bold mb-3">KAIRA</h4>
                        <p>Chúng tôi là cửa hàng thời trang phong cách hiện đại, mang đến trải nghiệm mua sắm tiện lợi và thân thiện.</p>
                        <div class="social-icons mt-3">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>

                    <!-- Liên kết nhanh -->
                    <div class="col-md-3 col-sm-6">
                        <h5 class="fw-semibold mb-3">LIÊN KẾT NHANH</h5>
                        <ul class="list-unstyled">
                            <li><a href="index.html">Trang chủ</a></li>
                            <li><a href="page/aboutus/aboutus.html">Giới thiệu</a></li>
                            <li><a href="page/faq/faq.html">Hỏi đáp</a></li>
                            <li><a href="page/recruitment/recruit.html">Tuyển dụng</a></li>
                            <li><a href="page/member/member.html">Membership</a></li>
                        </ul>
                    </div>

                    <!-- Thông tin liên hệ -->
                    <div class="col-md-3 col-sm-6">
                        <h5 class="fw-semibold mb-3">THÔNG TIN LIÊN HỆ</h5>
                        <p><i class="fas fa-map-marker-alt me-2"></i>123 Đường Lê Lợi, TP.HCM</p>
                        <p><i class="fas fa-envelope me-2"></i>contact@kairashop.com</p>
                        <p><i class="fas fa-phone me-2"></i>0901 234 567</p>
                    </div>

                    <!-- Bản đồ -->
                    <div class="col-md-3 col-sm-6">
                        <h5 class="fw-semibold mb-3">BẢN ĐỒ</h5>
                        <div class="map-embed">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.726643481827!2d106.6901211153343!3d10.75666499233459!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f3b5f6a90ed%3A0xf7b2b4f40e527417!2zMTIzIMSQLiBMw6ogTOG7m2ksIFTDom4gVGjhu5FuZyBI4buTbmcsIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaCwgSOG7kyBDaMOidSwgVMOibiBwaOG7kSBIw7JhIE5haQ!5e0!3m2!1svi!2s!4v1614089999097!5m2!1svi!2s" width="100%" height="180" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>

                </div>
                <div class="text-center py-3 border-top small">
                    © 2025 Kaira. Thiết kế lại bởi nhóm <strong>5 IS207</strong> | Dự án học phần Phát triển Web
                </div>
            </div>
        </footer>
</body>