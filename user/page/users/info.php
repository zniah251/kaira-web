<?php
session_start(); // Bắt đầu session ở đầu file
// Kiểm tra xem người dùng đã đăng nhập chưa
// Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['uid']) || $_SESSION['uid'] <= 0) {
    // Điều chỉnh đường dẫn đến trang đăng nhập của bạn
    header("Location: /e-web/user/page/sign-in/login2.php");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
// Khởi tạo mảng user_info với giá trị mặc định rỗng
$user_info = [
    'uname' => '',
    'email' => '',
    'phonenumber' => '',
    'address' => '', // Thêm trường địa chỉ
    'balance' => 0, // Khởi tạo trường balance với giá trị mặc định
    'google_id' => null, // Sẽ kiểm tra để xác định kết nối Google
    // 'facebook_id' => null, // Nếu có trường facebook_id trong bảng users
];

// Lấy thông tin người dùng từ database dựa vào $_SESSION['uid']
if (isset($_SESSION['uid']) && $_SESSION['uid'] > 0) {
    $uid = $_SESSION['uid'];
    // Chuẩn bị truy vấn để lấy thông tin người dùng từ bảng 'users'
    // Các cột có trong bảng users của bạn: uid, uname, email, phonenumber, address, password, google_id, created_at, updated_at, email_verified, rid
    $stmt = $conn->prepare("SELECT uname, email, phonenumber, address, balance, google_id FROM users WHERE uid = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    $db_user_info = $result->fetch_assoc();

    if ($db_user_info) {
        $user_info['uname'] = $db_user_info['uname'];
        $user_info['email'] = $db_user_info['email'];
        $user_info['phonenumber'] = $db_user_info['phonenumber'];
        $user_info['address'] = $db_user_info['address']; // Lấy địa chỉ
        $user_info['balance'] = $db_user_info['balance'] ?? 0; // Lấy số dư ví, mặc định là 0 nếu null
        $user_info['google_id'] = $db_user_info['google_id'];
        // $user_info['facebook_id'] = $db_user_info['facebook_id']; // Nếu có
    } else {
        // Trường hợp không tìm thấy người dùng dù có session UID
        // Có thể người dùng đã bị xóa hoặc session lỗi
        // Chuyển hướng về trang đăng nhập hoặc hiển thị lỗi
        session_destroy(); // Hủy session lỗi
        header("Location: ../../sign-in/login2.php");
        exit();
    }
    $stmt->close();
} else {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: ../../sign-in/login2.php");
    exit();
}

// Logic xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_uname = trim($_POST['uname'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $new_phonenumber = trim($_POST['phonenumber'] ?? '');
    $new_address = trim($_POST['address'] ?? '');

    $errors = [];

    // Validation cơ bản
    if (empty($new_uname)) {
        $errors[] = "Tên không được để trống.";
    }
    if (empty($new_email)) {
        $errors[] = "Email không được để trống.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ.";
    } else {
        // Chỉ kiểm tra trùng lặp email nếu email mới khác email hiện tại
        if ($new_email !== $user_info['email']) {
            // Kiểm tra xem email mới đã tồn tại trong database chưa
            $check_email = $conn->prepare("SELECT uid FROM users WHERE email = ? AND uid != ?");
            $check_email->bind_param("si", $new_email, $uid);
            $check_email->execute();
            $result = $check_email->get_result();
            
            if ($result->num_rows > 0) {
                $errors[] = "Email này đã được sử dụng bởi tài khoản khác. Vui lòng sử dụng email khác.";
            }
            $check_email->close();
        }
    }
    // Thêm các validation khác cho số điện thoại, địa chỉ nếu cần

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET uname = ?, email = ?, phonenumber = ?, address = ?, updated_at = NOW() WHERE uid = ?");
        $stmt->bind_param("ssssi", $new_uname, $new_email, $new_phonenumber, $new_address, $uid);

        if ($stmt->execute()) {
            // Cập nhật lại user_info sau khi update thành công để hiển thị ngay
            $user_info['uname'] = $new_uname;
            $user_info['email'] = $new_email;
            $user_info['phonenumber'] = $new_phonenumber;
            $user_info['address'] = $new_address;

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Cập nhật thông tin thành công!'];
        } else {
            error_log("Error updating user info: " . $stmt->error);
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Đã xảy ra lỗi khi cập nhật thông tin. Vui lòng thử lại.'];
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => implode('<br>', $errors)];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Kaira Shopping Cart</title>
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
    <link href="/e-web/user/css/tailwind-replacement.css" rel="stylesheet">
    <style>
        /* Reset và Base styles */
       body {
            font-family: 'Times New Roman', serif;
            color: black;
            /* Thêm fallback font */
        }
        h1, h2, h3, h4, h5 {
            font-family: 'Times New Roman', Times, serif !important;
            /* Sử dụng font Times New Roman cho tiêu đề */
        }

        /* Layout styles */
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .flex-1 { flex: 1 1 0%; }
        .grid { display: grid; }
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .gap-6 { gap: 1.5rem; }
        .gap-x-6 { column-gap: 1.5rem; }
        .gap-y-4 { row-gap: 1rem; }
        
        /* Responsive Grid */
        @media (min-width: 640px) {
            .sm\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

       /* Spacing */
       .p-6 { padding: 1.5rem; }
        .p-3 { padding: 0.75rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-3 { margin-bottom: 0.75rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-1 { margin-bottom: 0.25rem; }

        /* Typography */
        .text-2xl { font-size: 1.5rem; }
        .text-lg { font-size: 1.125rem; }
        .text-sm { font-size: 0.875rem; }
        .font-semibold { font-weight: 600; }
        .text-gray-800 { color: #1f2937; }
        .text-gray-700 { color: #374151; }
        .text-gray-600 { color: #4b5563; }
        .text-blue-600 { color: #2563eb; }

        /* Background & Colors */
        .bg-white { background-color: white; }
        .bg-gray-200 { background-color: #e5e7eb; }

        /* Border & Shadow */
        .rounded-lg { border-radius: 0.5rem; }
        .rounded-md { border-radius: 0.375rem; }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .border { border: 1px solid #d1d5db; }

        /* Form Elements */
        .input-group {
            margin-bottom: 1.5rem;
        }
        .input-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.5rem;
            font-family: 'Times New Roman', Times, serif !important;
        }
        .input-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            outline: none;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            font-family: 'Times New Roman', Times, serif !important;
        }
        .input-group input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }
        .input-group input[disabled] {
            background-color: #e5e7eb;
            cursor: not-allowed;
        }

        /* Button Styles */
        .btn-action {
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            font-family: 'Times New Roman', Times, serif !important;
        }
        .btn-primary-solid {
            background-color: #434343;
            color: white;
            border: none;
        }
        .btn-primary-solid:hover {
            background-color: #2f2f2f;
        }
        .btn-white-outline {
            background-color: white;
            border: 1px solid #d1d5db;
            color: #4b5563;
            font-family: 'Times New Roman', Times, serif !important;
        }
        .btn-white-outline:hover {
            background-color: #f9fafb;
        }
        .btn-danger-outline {
            background-color: white;
            border: 1px solid #ef4444;
            color: #ef4444;
            font-family: 'Times New Roman', Times, serif !important;
        }
        .btn-danger-outline:hover {
            background-color: #fef2f2;
            color: #dc2626;
            border-color: #dc2626;
        }

        /* Message Box Styles */
        .message-box {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            font-family: 'Times New Roman', Times, serif !important;
        }
        .message-box.success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }
        .message-box.error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        /* Hover Effects */
        .hover\:text-blue-800:hover {
            color: #1e40af;
        }
        .hover\:underline:hover {
            text-decoration: underline;
        }

        /* Focus Styles */
        .focus\:outline-none:focus {
            outline: none;
        }

        /* Justify Content */
        .justify-end {
            justify-content: flex-end;
        }
        .justify-center {
            justify-content: center;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 0.5rem;
            border: none;
        }
        .modal-header {
            border-bottom: none;
            padding: 1.5rem;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            border-top: none;
            padding: 1.5rem;
        }
        /* Ensure all general text elements use Times New Roman */
        p, span, div, small, label, a {
            font-family: 'Times New Roman', Times, serif !important;
        }
    </style>
</head>

<body>
    <?php include('../../../navbar.php'); ?>
    <div class="flex min-h-screen py-8 px-4 sm:px-6 lg:px-8" style="background-color: #f1f1f0;">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/e-web/sidebar2.php'; ?>
        <div class="flex-1 bg-white p-6 rounded-lg shadow-md" style="margin: 20px 0;">
            <h3 class="text-2xl font-semibold mb-6 text-gray-800" style="border-bottom: 1px solid rgba(0,0,0,0.1); padding-bottom: 15px;">Thông tin tài khoản</h3>

            <?php
            // Hiển thị thông báo (thành công/lỗi)
            if (isset($_SESSION['message'])) {
                $message_type = $_SESSION['message']['type'];
                $message_text = $_SESSION['message']['text'];
                echo "<div class='message-box {$message_type}'>{$message_text}</div>";
                unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
            }
            ?>

            <form action="info.php" method="POST">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 mb-4">
                    <div class="input-group">
                        <label for="uname">Tên & Họ</label>
                        <input type="text" id="uname" name="uname" value="<?php echo htmlspecialchars($user_info['uname']); ?>" class="form-input">
                    </div>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" class="form-input">
                    </div>
                    <div class="input-group">
                        <label for="phonenumber">Số điện thoại</label>
                        <input type="tel" id="phonenumber" name="phonenumber" placeholder="Nhập số điện thoại của bạn" value="<?php echo htmlspecialchars($user_info['phonenumber']); ?>" class="form-input">
                    </div>
                    <div class="input-group">
                        <label for="address">Địa chỉ</label>
                        <input type="text" id="address" name="address" placeholder="Nhập địa chỉ của bạn" value="<?php echo htmlspecialchars($user_info['address']); ?>" class="form-input">
                    </div>
                    <div class="input-group">
                        <label for="balance">Số dư ví điện tử</label>
                        <input type="text" id="balance" name="balance" value="<?php echo number_format($user_info['balance'], 0, ',', '.') . 'đ'; ?>" class="form-input" readonly>
                    </div>
                </div>

                <div class="mb-5">
                    <a href="#" class="text-blue-600 hover:text-blue-800 focus:outline-none focus:underline underline">Thay đổi mật khẩu</a>
                </div>

                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-2">Xóa tài khoản</h4>
                    <p class="text-sm text-gray-600 mb-4">Tài khoản của bạn sẽ xóa vĩnh viễn trên hệ thống mua sắm của KAIRA</p>
                    <button type="button" class="btn-action btn-danger-outline" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        Xóa tài khoản
                    </button>
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" class="btn-action btn-white-outline" onclick="window.history.back()">Quay về</button>
                    <button type="submit" class="btn-action btn-primary-solid">Cập nhật thông tin</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Thay đổi mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="mb-4">
                            <label for="old_password" class="form-label">Mật khẩu cũ</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Nhập mật khẩu cũ của bạn">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="old_password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới của bạn">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="confirm_new_password" class="form-label">Nhập lại mật khẩu mới</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Nhập lại mật khẩu mới của bạn">
                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirm_new_password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center border-0"> <button type="button" class="btn btn-dark" id="confirmChangePassword">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Xác nhận xóa tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-gray-700 mb-4">
                        Bạn có chắc chắn muốn xóa tài khoản của mình? Hành động này sẽ xóa vĩnh viễn tất cả dữ liệu liên quan đến tài khoản của bạn và không thể hoàn tác.
                    </p>
                    <div class="mb-4">
                        <label for="delete_password" class="form-label">Vui lòng nhập mật khẩu của bạn để xác nhận:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="delete_password" name="delete_password" placeholder="Nhập mật khẩu của bạn">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="delete_password">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div id="deleteAccountMessage" class="text-red-500 text-sm mt-2" style="display: none;"></div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteAccount">Xóa tài khoản</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../../footer.php'); ?>
    <script>
        // JavaScript để điều khiển modal và nút ẩn/hiện mật khẩu
        document.addEventListener("DOMContentLoaded", function() {
            // Lấy thẻ <a> "Thay đổi mật khẩu"
            const changePasswordLink = document.querySelector('a.underline'); // Hoặc dùng ID nếu bạn thêm ID cho nó

            // Lấy modal Bootstrap
            const changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));

            // Gắn sự kiện click vào liên kết để mở modal
            changePasswordLink.addEventListener('click', function(e) {
                e.preventDefault(); // Ngăn chặn hành vi mặc định của liên kết
                changePasswordModal.show();
            });

            // Xử lý ẩn/hiện mật khẩu
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.dataset.target;
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
            // Xử lý khi click nút Xác nhận trong modal
            document.getElementById('confirmChangePassword').addEventListener('click', function() {
                const oldPassword = document.getElementById('old_password').value;
                const newPassword = document.getElementById('new_password').value;
                const confirmNewPassword = document.getElementById('confirm_new_password').value;

                // Validation phía client
                if (oldPassword === '' || newPassword === '' || confirmNewPassword === '') {
                    alert("Vui lòng điền đầy đủ tất cả các trường!");
                    return;
                }
                if (newPassword !== confirmNewPassword) {
                    alert("Mật khẩu mới và xác nhận mật khẩu không khớp!");
                    return;
                }
                // (Optional) Thêm validation độ mạnh mật khẩu ở đây
                // Ví dụ: if (newPassword.length < 8) { alert("Mật khẩu mới phải có ít nhất 8 ký tự!"); return; }

                // Gửi AJAX request đến server
                fetch('/e-web/user/page/ajax_handlers/process_change_password.php', { // URL đến file PHP xử lý
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json', // Quan trọng: gửi dữ liệu dạng JSON
                            'X-Requested-With': 'XMLHttpRequest' // Dùng để xác định đây là AJAX request ở phía server
                        },
                        body: JSON.stringify({ // Chuyển dữ liệu thành chuỗi JSON
                            old_password: oldPassword,
                            new_password: newPassword
                        }),
                    })
                    .then(response => {
                        if (!response.ok) { // Kiểm tra nếu HTTP status code không phải 2xx
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json(); // Phân tích phản hồi JSON
                    })
                    .then(data => {
                        if (data.success) {
                            alert("Mật khẩu đã được thay đổi thành công!");
                            changePasswordModal.hide(); // Đóng modal
                            document.getElementById('changePasswordForm').reset(); // Xóa dữ liệu trong form
                        } else {
                            alert("Lỗi: " + data.message); // Hiển thị thông báo lỗi từ server
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi gửi yêu cầu AJAX:', error);
                        alert("Có lỗi xảy ra khi thay đổi mật khẩu. Vui lòng thử lại.");
                    });
            });
            // Lấy modal Xóa tài khoản
            const deleteAccountModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
            const deletePasswordField = document.getElementById('delete_password');
            const deleteAccountMessage = document.getElementById('deleteAccountMessage');

            // Xử lý khi modal xóa tài khoản được ẩn (reset form)
            document.getElementById('deleteAccountModal').addEventListener('hidden.bs.modal', function() {
                deletePasswordField.value = ''; // Xóa mật khẩu đã nhập
                deleteAccountMessage.style.display = 'none'; // Ẩn thông báo lỗi
                deleteAccountMessage.innerHTML = ''; // Xóa nội dung thông báo
            });

            // Xử lý khi click nút "Xóa tài khoản" trong modal
            document.getElementById('confirmDeleteAccount').addEventListener('click', function() {
                const passwordToDelete = deletePasswordField.value;
                deleteAccountMessage.style.display = 'none'; // Ẩn thông báo cũ
                deleteAccountMessage.innerHTML = '';

                if (passwordToDelete === '') {
                    deleteAccountMessage.innerHTML = "Vui lòng nhập mật khẩu của bạn để xác nhận.";
                    deleteAccountMessage.style.display = 'block';
                    return;
                }

                // Gửi AJAX request đến server
                fetch('/e-web/user/page/ajax_handlers/process_delete_account.php', { // Tạo file này ở Bước 3
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            password: passwordToDelete
                        }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert("Tài khoản của bạn đã được xóa thành công!");
                            // Sau khi xóa thành công, chuyển hướng người dùng đến trang chủ hoặc trang đăng ký
                            window.location.href = '/e-web/user/'; // Hoặc trang đăng nhập / login2.php
                        } else {
                            deleteAccountMessage.innerHTML = data.message; // Hiển thị lỗi từ server
                            deleteAccountMessage.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi gửi yêu cầu xóa tài khoản:', error);
                        deleteAccountMessage.innerHTML = "Có lỗi xảy ra khi xóa tài khoản. Vui lòng thử lại.";
                        deleteAccountMessage.style.display = 'block';
                    });
            });
        });
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.js"></script>
</body>

</body>