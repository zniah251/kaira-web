
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - The C.I.U</title>
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
    <!-- Thêm toast notification -->
    <div id="success-toast" class="toast-notification">
        <div class="toast-content">
            <span class="toast-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" />
                </svg>
            </span>
            <span id="toast-message">Đăng ký thành công!</span>
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
            <p class="subtitle">Đăng ký ngay để có trải nghiệm mua sắm tuyệt vời cùng chúng tôi.</p>
    
            <div class="login-tabs active-password" id="signinTabs">
                <div class="login-tab-item active" data-tab="signin">Đăng ký</div>
            </div>
            <div id="passwordTabContent" class="tab-content active">
                <div class="form-group">
                    <label for="phone-email-password">Họ và tên</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user icon"></i>
                        <input type="text" id="uname" placeholder="Nhập họ và tên của bạn">
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone-email-password">Email</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user icon"></i>
                        <input type="text" id="email" placeholder="Nhập email của bạn">
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone-email-password">Số điện thoại</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user icon"></i>
                        <input type="text" id="phonenumber" placeholder="Nhập số điện thoại của bạn">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock icon"></i>
                        <input type="password" id="password1" placeholder="Nhập mật khẩu của bạn">
                        <span class="toggle-password" onclick="togglePasswordVisibility('password1', this)">
                            <i class="fa-solid fa-eye-slash"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password2">Nhập lại mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock icon"></i>
                        <input type="password" id="password2" placeholder="Nhập lại mật khẩu của bạn">
                        <span class="toggle-password" onclick="togglePasswordVisibility('password2', this)">
                            <i class="fa-solid fa-eye-slash"></i>
                        </span>
                    </div>
                </div>
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember-me-password">
                        <label for="remember-me-password">Nhớ đăng nhập</label>
                    </div>
                    <a href="#" class="forgot-password">Quên mật khẩu</a>
                </div>
            </div>

            <button type="submit" class="login-button">Đăng ký</button>

            <p class="signup-link">
                Bạn đã có tài khoản? <a href="./login2.php">Đăng nhập ngay</a>
            </p>
        </div>
    </div>


</body>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
    function togglePasswordVisibility(inputId, toggleButton) {
        const passwordInput = document.getElementById(inputId);
        const icon = toggleButton.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
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

    // Xử lý sự kiện click cho nút Đăng ký thủ công
    document.querySelector('.login-button').addEventListener('click', async function(event) {
        event.preventDefault(); // Ngăn chặn form gửi đi mặc định

        // 1. Thu thập dữ liệu từ các trường input
        const uname = document.getElementById('uname').value.trim();
        const email = document.getElementById('email').value.trim();
        const phonenumber = document.getElementById('phonenumber').value.trim();
        const password = document.getElementById('password1').value;
        const confirmPassword = document.getElementById('password2').value;

        // 2. Frontend Validation
        if (!uname || !email || !phonenumber || !password || !confirmPassword) {
            alert('Vui lòng điền đầy đủ tất cả các trường.');
            return;
        }

        if (password !== confirmPassword) {
            alert('Mật khẩu và xác nhận mật khẩu không khớp.');
            return;
        }

        if (password.length < 6) { // Ví dụ: mật khẩu tối thiểu 6 ký tự
            alert('Mật khẩu phải có ít nhất 6 ký tự.');
            return;
        }

        // Regex cho email đơn giản
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Email không hợp lệ.');
            return;
        }

        // Regex cho số điện thoại (ví dụ: chỉ số, 10 hoặc 11 chữ số)
        const phoneRegex = /^\d{10,11}$/;
        if (!phoneRegex.test(phonenumber)) {
            alert('Số điện thoại không hợp lệ (chỉ chấp nhận 10 hoặc 11 chữ số).');
            return;
        }

        try {
            // 3. Gửi dữ liệu đăng ký đến backend (handlers.php)
            const response = await fetch('/e-web/user/page/sign-in/handlers.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'register', // Thêm action để backend biết là yêu cầu đăng ký
                    uname: uname,
                    email: email,
                    phonenumber: phonenumber,
                    password: password
                }),
            });

            const data = await response.json(); // Phân tích phản hồi JSON từ PHP

            // 4. Xử lý phản hồi từ backend
            if (response.ok && data.success) {
                showToast('Đăng ký thành công!'); // Hiển thị toast thay vì alert
                setTimeout(() => {
                    window.location.href = '/e-web/user/index.php'; // Chuyển hướng đến trang dashboard
                }, 2000); // Chuyển hướng sau 2 giây
            } else {
                const errorMessage = data.message || 'Lỗi không xác định từ server.';
                showToast('Đăng ký thất bại: ' + errorMessage);
            }
        } catch (error) {
            console.error('Detailed error:', error);
            console.log('Error stack:', error.stack);
            showToast('Đã xảy ra lỗi kết nối. Vui lòng thử lại.');
        }
    });
    // Xử lý phản hồi từ Google Sign-In
    function handleCredentialResponse(response) {
    // Gửi credential (JWT) về server PHP để xác thực và đăng nhập/đăng ký
    fetch('/e-web/user/page/sign-in/handlers.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'google_login',
            credential: response.credential
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Đăng nhập Google thành công!');
            window.location.href = '/e-web/user/index.php';
        } else {
            alert('Đăng nhập Google thất bại: ' + data.message);
        }
    });
}
</script>

</html>