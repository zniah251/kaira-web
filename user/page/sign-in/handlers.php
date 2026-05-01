<?php

session_start(); // Luôn bắt đầu session ở đầu file nếu bạn muốn sử dụng session để đăng nhập
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Luôn gửi phản hồi JSON
// Cần thiết cho CORS trong phát triển, KHÔNG NÊN DÙNG * TRONG PRODUCTION! Thay bằng domain của bạn.
// Ví dụ: header('Access-Control-Allow-Origin: http://localhost:80'); // Nếu frontend của bạn chạy trên cổng 80
header('Access-Control-Allow-Origin:  http://localhost:8888'); 
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Xử lý preflight request của CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Lấy dữ liệu JSON từ request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Kiểm tra xem 'action' có được gửi lên không
if (!isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Hành động không xác định.']);
    http_response_code(400);
    exit();
}

// Bất kỳ file nào bạn include để kết nối DB hoặc cấu hình
// Đảm bảo đường dẫn này đúng từ vị trí của file handlers.php
// Ví dụ: nếu handlers.php nằm trong thư mục con /api/ và connect.php ở gốc dự án
include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php"; 

// --- Bắt đầu từ đây ---
// ===============================================
// Xử lý ĐĂNG KÝ THỦ CÔNG
// ===============================================
if ($data['action'] === 'register') {
    // Kiểm tra dữ liệu đầu vào
    if (!isset($data['uname'], $data['email'], $data['phonenumber'], $data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng cung cấp đầy đủ thông tin đăng ký.']);
        http_response_code(400);
        exit();
    }

    $uname = trim($data['uname']);
    $email = trim(strtolower($data['email']));
    $phonenumber = trim($data['phonenumber']);
    $password = $data['password'];

    // Server-side Validation
    if (empty($uname) || empty($email) || empty($phonenumber) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Các trường không được để trống.']);
        http_response_code(400);
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ.']);
        http_response_code(400);
        exit();
    }
    if (strlen($password) < 6) { // Phải khớp với validation frontend
        echo json_encode(['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.']);
        http_response_code(400);
        exit();
    }
    // Bạn có thể thêm validation regex cho phonenumber ở đây

    try {
        // Kiểm tra email hoặc số điện thoại đã tồn tại chưa
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ? OR phonenumber = ?");
        $stmt->bind_param("ss", $email, $phonenumber);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo json_encode(['success' => false, 'message' => 'Email hoặc số điện thoại đã được đăng ký.']);
            http_response_code(409); // Conflict
            exit();
        }

        // Băm (hash) mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Chèn dữ liệu người dùng mới vào DB
        $stmt = $conn->prepare("INSERT INTO users (uname, email, phonenumber, password, created_at, updated_at, email_verified, rid) VALUES (?, ?, ?, ?, NOW(), NOW(), 0, 2)");
        $stmt->bind_param("ssss", $uname, $email, $phonenumber, $hashed_password);
        $stmt->execute();
        $new_user_id = $conn->insert_id;
        $stmt->close();

        // Tạo phiên đăng nhập (session)
        $_SESSION['uid'] = $new_user_id;
        $_SESSION['uname'] = $uname;
        $_SESSION['email'] = $email;

        echo json_encode(['success' => true, 'message' => 'Đăng ký thành công!', 'user' => ['uid' => $new_user_id, 'uname' => $uname, 'email' => $email]]);
        http_response_code(201); // Created

    } catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    http_response_code(500);
}

}
// Xử lý ĐĂNG NHẬP THỦ CÔNG (PHẦN MỚI)
// ===============================================
else if ($data['action'] === 'login') {
    if (!isset($data['identifier'], $data['password'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng cung cấp email/số điện thoại và mật khẩu.']);
        http_response_code(400);
        exit();
    }

    $identifier = trim($data['identifier']);
    $password = $data['password'];

    // Bật exception cho MySQLi nếu chưa bật (nên đặt dòng này ở đầu file, chỉ cần 1 lần)
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        // Kiểm tra identifier là email hay số điện thoại
        $is_email = filter_var($identifier, FILTER_VALIDATE_EMAIL);

        if ($is_email) {
            $stmt = $conn->prepare("SELECT uid, uname, email, password, rid FROM users WHERE email = ?"); // Đã sửa từ 'users' thành 'user' nếu tên bảng là 'user'
        } else {
            $stmt = $conn->prepare("SELECT uid, uname, email, password, rid FROM users WHERE phonenumber = ?"); // Đã sửa từ 'users' thành 'user' nếu tên bảng là 'user'
        }
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['uid'] = $user['uid'];
            $_SESSION['uname'] = $user['uname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['rid'] = $user['rid']; 
            // THAY ĐỔI 3: Thêm logic chuyển hướng dựa trên rid
            $redirect_url = '';
            if ($user['rid'] == 1) { // Giả sử rid = 1 là quản trị viên
                $redirect_url = '/e-web/admin/pages/dashboard/dashboard-test.php';
            } else { // Các vai trò khác (người dùng thông thường)
                $redirect_url = '/e-web/user/index.php'; // Trang mặc định cho người dùng
            }

            echo json_encode([
                'success' => true,
                'message' => 'Đăng nhập thành công!',
                'user' => [
                    'uid' => $user['uid'],
                    'uname' => $user['uname'],
                    'email' => $user['email'],
                    'rid' => $user['rid'] // Thêm rid vào phản hồi JSON
                ],
                'redirect' => $redirect_url // THAY ĐỔI 4: Trả về URL chuyển hướng
            ]);
            http_response_code(200);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Email/Số điện thoại hoặc mật khẩu không đúng.']);
            http_response_code(401);
            exit();
        }
    } catch (Exception $e) {
        error_log("Manual login error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi server khi đăng nhập thủ công.']);
        http_response_code(500);
        exit();
    }
}

?>