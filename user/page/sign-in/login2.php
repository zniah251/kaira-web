

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - The C.I.U</title>
    <link rel="stylesheet" href="login2.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Toast Notification Styles */
        .toast-notification {
            display: none;
            position: fixed;
            top: 32px;
            right: 32px;
            z-index: 9999;
            animation: slideIn 0.5s ease-out;
        }

        .toast-content {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            padding: 16px 24px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            font-size: 1.2rem;
            font-weight: 500;
        }

        .toast-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #4ade80;
            border-radius: 50%;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .toast-notification.hide {
            animation: fadeOut 0.5s ease-out forwards;
        }
    </style>
</head>

<body>
    <!-- Toast Notification -->
    <div id="success-toast" class="toast-notification">
        <div class="toast-content">
            <span class="toast-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" />
                </svg>
            </span>
            <span id="toast-message">Đăng nhập thành công!</span>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="image-section">
            <div class="image-section-content">
                <div class="image-placeholder">
                    <!-- Thêm thẻ img để test -->
                    <img src="/e-web/admin/assets/images/login2.jpg" alt="Login Image" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
            </div>
        </div>
        <div class="login-container">
            <h1 class="logo">KAIRA</h1>
            <p class="welcome-text">Welcome to KAIRA!</p>
            <p class="subtitle">Đăng nhập ngay để có trải nghiệm mua sắm tuyệt vời cùng chúng tôi.</p>

            <div id="passwordTabContent" class="tab-content active">
                <div class="form-group">
                    <label for="phone-email-password">Số điện thoại hoặc email</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user icon"></i>
                        <input type="text" id="phone-email-password" placeholder="Nhập số điện thoại hoặc email của bạn">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock icon"></i>
                        <input type="password" id="password" placeholder="Nhập mật khẩu của bạn">
                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="fa-solid fa-eye-slash"></i>
                        </span>
                    </div>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember-me-password">
                        <label for="remember-me-password">Nhớ đăng nhập</label>
                    </div>
                    <a href="/e-web/user/page/sign-in/forget_password.php" class="forgot-password">Quên mật khẩu</a>
                </div>
            </div>

            <button type="submit" class="login-button" id="login-button">Đăng nhập</button>

            <p class="signup-link">
                Bạn chưa có tài khoản? <a href="/e-web/user/page/sign-in/sign-in.php">Đăng ký ngay</a>
            </p>
        </div>
    </div>


</body>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        const savedLogin = localStorage.getItem('rememberLogin');
        if (savedLogin) {
            try {
                const loginObj = JSON.parse(savedLogin);
                document.getElementById('phone-email-password').value = loginObj.identifier || '';
                document.getElementById('password').value = loginObj.password || '';
                document.getElementById('remember-me-password').checked = true;
            } catch (e) {
                // Nếu lỗi parse, xóa localStorage này đi cho an toàn
                localStorage.removeItem('rememberLogin');
            }
        }
    });

    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        }
    }
    // BẮT ĐẦU PHẦN JAVASCRIPT MỚI CHO ĐĂNG NHẬP
    document.getElementById('login-button').addEventListener('click', async function(event) {
        event.preventDefault(); // Ngăn chặn hành vi mặc định của button

        // Sửa lại ID cho đúng với HTML
        const identifier = document.getElementById('phone-email-password').value.trim();
        const password = document.getElementById('password').value;
        const rememberMe = document.getElementById('remember-me-password').checked;

        console.log('Login attempt with:', {
            identifier
        }); // Log để debug

        // Frontend Validation cơ bản
        if (!identifier || !password) {
            alert('Vui lòng nhập đầy đủ email/số điện thoại và mật khẩu.');
            return;
        }

        try {
            const requestData = {
                action: 'login',
                identifier: identifier,
                password: password
            };

            console.log('Sending login request:', requestData); // Log data gửi đi

            // Gửi dữ liệu đăng nhập đến handlers.php
            const response = await fetch('/e-web/user/page/sign-in/handlers.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData)
            });

            console.log('Response status:', response.status); // Log status code

            const data = await response.json();
            console.log('Response data:', data); // Log response data

            if (response.ok && data.success) {
                // Nếu người dùng chọn "Nhớ đăng nhập", lưu lại
                if (rememberMe) {
                    localStorage.setItem('rememberLogin', JSON.stringify({
                        identifier: identifier,
                        password: password
                    }));
                } else {
                    // Nếu không chọn, xóa đi
                    localStorage.removeItem('rememberLogin');
                }

                // Hiển thị toast thay vì alert
                showToast('Đăng nhập thành công! Chào mừng ' + data.user.uname);
                
                // Đợi 1.5 giây để người dùng thấy thông báo rồi mới chuyển trang
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                // Đăng nhập thất bại, KHÔNG lưu localStorage
                localStorage.removeItem('rememberLogin');
                alert('Đăng nhập thất bại: ' + (data.message || 'Lỗi không xác định từ server.'));
            }
        } catch (error) {
            console.error('Detailed error:', error);
            console.log('Error stack:', error.stack);
            alert('Đã xảy ra lỗi kết nối. Vui lòng thử lại.');
        }
    });
    // KẾT THÚC PHẦN JAVASCRIPT MỚI CHO ĐĂNG NHẬP

    // Hàm hiển thị toast
    function showToast(message, duration = 3000) {
        const toast = document.getElementById('success-toast');
        const toastMessage = document.getElementById('toast-message');
        toastMessage.textContent = message;
        
        // Hiển thị toast
        toast.style.display = 'block';
        
        // Tự động ẩn sau duration
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => {
                toast.style.display = 'none';
                toast.classList.remove('hide');
            }, 500);
        }, duration);
    }
</script>

</html>