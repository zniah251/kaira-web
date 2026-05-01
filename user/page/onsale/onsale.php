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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="../../css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../../css/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="../../index.html">
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

        .container_onsale {
            max-width: 1200px;
            margin: auto;
            padding: 40px 20px;
        }

        .onsale-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 32px;
            font-weight: bold;
        }

        .onsale-header span {
            display: inline-block;
            background-color: #d70018;
            color: white;
            font-size: 20px;
            padding: 5px 10px;
            border-radius: 6px;
        }

        .sort-filter {
            display: flex;
            align-items: center;
            margin: 30px 0;
        }

        .sort-filter label {
            margin-right: 10px;
            font-size: 16px;
            color: #333;
        }

        .sort-filter select {
            padding: 8px 12px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
        }

        .empty-sale {
            text-align: center;
            margin-top: 60px;
        }

        .empty-sale img {
            max-width: 250px;
            margin-bottom: 30px;
        }

        .empty-sale h3 {
            font-weight: bold;
            font-size: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .empty-sale p {
            font-size: 15px;
            color: #444;
        }
    </style>
</head>

<body>
    <?php include('../../../navbar.php'); ?>
    <nav class="breadcrumb">
        <a href="../../index.php">Trang chủ</a> <span class="breadcrumb-separator">&gt;</span> <span>ON SALE</span>
    </nav>

    <body>
        <div class="container_onsale">
            <div class="onsale-header">
                <span>%</span>
                On Sale
            </div>

            <div class="sort-filter">
                <label for="sort">Sắp xếp theo</label>
                <select id="sort">
                    <option value="low-high">Giá thấp nhất</option>
                    <option value="high-low">Giá cao nhất</option>
                    <option value="new">Mới nhất</option>
                </select>
            </div>

            <div class="empty-sale">
                <img src="../onsale/img-onsale/empty-sale.png" alt="Sale off sắp diễn ra">
                <h3>Chương trình Sale off sắp diễn ra!</h3>
                <p>Chương trình Sale off của KAIRA sẽ diễn ra trong thời gian sắp tới, bạn cùng theo dõi và đón mua nhé!</p>
            </div>
        </div>
         <?php include('../../../footer.php'); ?>
    </body>