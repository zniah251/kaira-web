<?php
session_start();
require_once("../../../connect.php");

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: /e-web/user/page/sign-in/login2.php");
    exit();
}

$orderId = $_GET['orderId'] ?? '';
$fullname = $_GET['fullname'] ?? '';
$address = $_GET['address'] ?? '';
$phone = $_GET['phone'] ?? '';

if (empty($orderId)) {
    header("Location: /e-web/user/page/cart/cart.php");
    exit();
}

// Fetch order details
$stmt = $conn->prepare("SELECT o.*, u.email FROM orders o JOIN users u ON o.uid = u.uid WHERE o.oid = ? AND o.uid = ?");
$stmt->bind_param("ii", $orderId, $_SESSION['uid']);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    header("Location: /e-web/user/page/cart/cart.php");
    exit();
}

// Fetch order details
$stmt = $conn->prepare("SELECT od.*, p.title, p.thumbnail FROM order_detail od JOIN product p ON od.pid = p.pid WHERE od.oid = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$orderDetails = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán thành công - KAIRA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../../user/css/vendor.css">
    <link rel="stylesheet" type="text/css" href="../../style.css">
    <link rel="stylesheet" type="text/css" href="../../css/normalize.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif !important;
            background-color: #f8f9fa;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Times New Roman', Times, serif !important;
        }
        .success-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .success-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .success-body {
            padding: 40px;
        }
        .order-summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .product-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }
        .btn-primary {
            background-color: #000;
            border: none;
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif !important;
        }
        .btn-primary:hover {
            background-color: #333;
        }
        .btn-outline-secondary {
            font-family: 'Times New Roman', Times, serif !important;
        }
        p, span, div, small {
            font-family: 'Times New Roman', Times, serif !important;
        }
    </style>
</head>
<body>
    <?php include('../../../navbar.php'); ?>
    
    <div class="container">
        <div class="success-container">
            <div class="success-header">
                <i class="fas fa-check-circle fa-4x mb-3"></i>
                <h2>Thanh toán thành công!</h2>
                <p class="mb-0">Đơn hàng #<?= $orderId ?> đã được thanh toán bằng ví điện tử</p>
            </div>
            
            <div class="success-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-user me-2"></i>Thông tin giao hàng</h5>
                        <p><strong>Họ tên:</strong> <?= htmlspecialchars($fullname) ?></p>
                        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($address) ?></p>
                        <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($phone) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-credit-card me-2"></i>Thông tin thanh toán</h5>
                        <p><strong>Phương thức:</strong> Ví điện tử</p>
                        <p><strong>Trạng thái:</strong> <span class="badge bg-success">Đã thanh toán</span></p>
                        <p><strong>Tổng tiền:</strong> <strong class="text-success"><?= number_format($order['totalfinal'], 0, ',', '.') ?>đ</strong></p>
                    </div>
                </div>
                
                <div class="order-summary">
                    <h5><i class="fas fa-shopping-bag me-2"></i>Chi tiết đơn hàng</h5>
                    <?php while ($item = $orderDetails->fetch_assoc()): ?>
                        <div class="product-item">
                            <img src="<?= htmlspecialchars($item['thumbnail']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="product-image">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= htmlspecialchars($item['title']) ?></h6>
                                <small class="text-muted">
                                    Size: <?= htmlspecialchars($item['size']) ?> | 
                                    Màu: <?= htmlspecialchars($item['color']) ?> | 
                                    Số lượng: <?= $item['quantity'] ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <strong><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</strong>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="text-center mt-4">
                    <a href="/e-web/user/page/users/manage_orders.php" class="btn btn-primary me-3">
                        <i class="fas fa-list me-2"></i>Xem đơn hàng
                    </a>
                    <a href="/e-web/user/index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../../footer.php'); ?>
</body>
</html>