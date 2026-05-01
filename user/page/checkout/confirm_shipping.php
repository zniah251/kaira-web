<?php
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";

// Kiểm tra session đăng nhập
session_start();
if (!isset($_SESSION['uid'])) {
    header("Location: /e-web/user/page/login2.php");
    exit();
}

$uid = $_SESSION['uid'];

// Lấy thông tin từ form checkout.php được submit
$fullname = $_POST['fullname'] ?? '';
$address = $_POST['address'] ?? '';
$phone = $_POST['phone'] ?? '';

// Nếu không có thông tin từ form, lấy từ database
if (empty($fullname) && empty($address) && empty($phone)) {
    $sql = "SELECT * FROM users WHERE uid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $fullname = $user['uname'] ?? 'Khách hàng';
    $phone = $user['phonenumber'] ?? 'Chưa có';
    $address = $user['address'] ?? 'Không rõ';
}

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_shipping'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Luôn cập nhật thông tin user khi xác nhận đặt hàng
    $update_sql = "UPDATE users SET 
                uname = ?,
                phonenumber = ?,
                address = ?
                WHERE uid = ?";
                
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $fullname, $phone, $address, $uid);
    
    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error;
        exit();
    }
    
    // Chuyển hướng đến trang cảm ơn
    header("Location: thankyou.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận giao hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
      background: linear-gradient(to right, #fdfbfb, #ebedee);
      font-family: 'Times New Roman', serif;
    }
    .container-box {
      max-width: 600px;
      margin: 80px auto;
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      animation: slideUp 0.4s ease;
    }
    .btn-confirm {
      background: black;
      color: white;
      font-weight: bold;
      padding: 12px;
      border: none;
      border-radius: 30px;
      width: 100%;
      font-size: 16px;
    }
    .btn-confirm:hover {
      background: #333;
    }
    @keyframes slideUp {
      from {opacity: 0; transform: translateY(30px);}
      to {opacity: 1; transform: translateY(0);}
    }
    </style>
</head>
<body>
    <div class="container-box text-center">
        <h3 class="mb-4">Xác nhận địa chỉ giao hàng</h3>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label class="form-label"><strong>Họ tên:</strong></label>
                <input type="text" class="form-control" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Số điện thoại:</strong></label>
                <input type="tel" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label"><strong>Địa chỉ:</strong></label>
                <textarea class="form-control" name="address" required><?= htmlspecialchars($address) ?></textarea>
            </div>
            
            <!-- Thêm input để phân biệt form submission -->
            <input type="hidden" name="confirm_shipping" value="1">
            
            <button type="submit" class="btn-confirm mt-4">Xác nhận giao hàng</button>
        </form>
    </div>
</body>
</html>
