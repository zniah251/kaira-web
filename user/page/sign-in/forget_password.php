<!--D:\>cd D:\xampp\htdocs\e-web\user\page\sign-in

D:\xampp\htdocs\e-web\user\page\sign-in>node mailer.js -->

<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . "/e-web/connect.php";
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lấy lại mật khẩu - KAIRA</title>
    <link rel="stylesheet" href="login2.css" />
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

        .login {
            font-weight: bold;
            color: black;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .reset-button {
            height: 20px;
            width: 50%;
            padding: 15px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.95em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            font-family: 'Times New Roman', Times, serif;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin: auto;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        #submit-otp,
        #submit-password {
            height: 20px;
            width: 50%;
            padding: 15px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.95em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            font-family: 'Times New Roman', Times, serif;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin: auto;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .reset-button:hover {
            background-color: #333;
        }

        label[for="otp"] {
            display: block;
            margin-top: 8px;
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
            <span id="toast-message">Đổi mật khẩu thành công!</span>
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

            <div class="login-tabs active-password" id="loginTabs">
                <div class="login-tab-item active" data-tab="password">Lấy lại mật khẩu</div>
            </div>
            <!-- Nhập email -->
            <div class="form-group" id="email-group">
                <div id="emaillabel">
                    <label for="phone-email-password">Nhập email</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user icon"></i>
                        <input type="text" id="phone-email-password" placeholder="Nhập email của bạn">
                    </div>
                </div>

                <div class="form-group" id="otp-group" style="display: none;">
                    <label for="otp">Nhập mã xác nhận</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock icon"></i>
                        <input type="text" id="otp-input" placeholder="Nhập mã xác thực đã gửi đến email của bạn">
                    </div>
                </div>
                <!-- Nhập mật khẩu mới -->
                <div class="form-group" id="new-password-group" style="display: none;">
                    <label for="password">Nhập mật khẩu mới</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock icon"></i>
                        <input type="password" id="password" placeholder="Nhập mật khẩu mới của bạn">
                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class="fa-solid fa-eye-slash"></i>
                        </span>
                    </div>
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="form-group" id="confirm-password-group" style="display: none;">
                    <label for="password1">Xác nhận mật khẩu mới</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock icon"></i>
                        <input type="password" id="password1" placeholder="Nhập lại mật khẩu mới của bạn">
                        <span class="toggle-password" onclick="togglePasswordVisibility1()">
                            <i class="fa-solid fa-eye-slash"></i>
                        </span>
                    </div>
                </div>

                <div class="form-options">
                    <a href="/e-web/user/page/sign-in/login2.php" class="login">Đăng nhập</a>
                </div>
            </div>

            <button type="submit" class="reset-button" id="reset-password">Lấy mã xác thực </button>
            <button type="submit" class="reset-button" id="submit-otp" style="display: none;">Xác nhận mã</button>
            <button type="submit" class="reset-button" id="submit-password" style="display: none;">Xác nhận mật khẩu mới</button>

            <p class="signup-link">
                Bạn chưa có tài khoản? <a href="/e-web/user/page/sign-in/sign-in.php">Đăng ký ngay</a>
            </p>
        </div>
    </div>


    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("password");
            const icon = passwordInput.nextElementSibling.querySelector("i");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }

        function togglePasswordVisibility1() {
            const passwordInput = document.getElementById("password1");
            const icon = passwordInput.nextElementSibling.querySelector("i");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }

        document.getElementById('reset-password').addEventListener('click', async function(event) {
            event.preventDefault();
            const email = document.getElementById('phone-email-password').value.trim();

            if (!email) {
                alert("Vui lòng nhập email để lấy lại mật khẩu.");
                return;
            }

            try {
                // B1: Kiểm tra email có tồn tại không
                const verifyRes = await fetch('verify_email.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email
                    })
                });

                const verifyData = await verifyRes.json();

                if (!verifyData.success) {
                    alert(verifyData.message || "Email không tồn tại.");
                    return;
                }

                // B2: Sinh mã xác thực
                const verificationCode = Math.floor(100000 + Math.random() * 900000);

                // B3: Gửi mã xác thực qua Node.js API
                const sendMailRes = await fetch('http://localhost:3001/send-verification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email,
                        verificationCode
                    })
                });

                if (!sendMailRes.ok) {
                    const err = await sendMailRes.text();
                    alert("Gửi email thất bại: " + err);
                    return;
                }

                // B4: Gửi mã OTP sang PHP để lưu session
                const storeRes = await fetch('store_otp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include', // ✅ cần thiết để giữ session
                    body: JSON.stringify({
                        email,
                        otp_code: verificationCode,
                        action: 'store'
                    })
                });


                if (storeRes.ok) {
                    showToast("Mã xác thực đã được gửi tới email của bạn.");
                    // Ẩn input email
                    document.getElementById('emaillabel').style.display = 'none';
                    document.getElementById('reset-password').style.display = 'none';

                    // Hiện các input nhập OTP 
                    document.getElementById('otp-group').style.display = 'block';
                    document.getElementById('submit-otp').style.display = 'block';
                } else {
                    alert("Gửi email thành công nhưng không lưu được OTP.");
                }
            } catch (error) {
                alert("Có lỗi khi kết nối đến hệ thống.");
                console.error(error);
            }
        });

        document.getElementById('submit-otp').addEventListener('click', async function(event) {
            event.preventDefault();
            const email = document.getElementById('phone-email-password').value.trim();
            const otp = document.getElementById('otp-input').value.trim();

            if (!otp) {
                alert("Vui lòng nhập mã xác thực.");
                return;
            }

            try {
                // Gửi OTP và email sang PHP để kiểm tra
                const res = await fetch('store_otp.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include', // ✅ cần thiết để giữ session
                    body: JSON.stringify({
                        email,
                        otp_code: otp,
                        action: 'verify'
                    })
                });

                const data = await res.json();
                if (data.success) {
                    alert("Bạn đã xác thực thành công mã OTP, vui lòng nhập mật khẩu mới.");
                    // Ẩn phần nhập OTP, hiện phần nhập mật khẩu mới
                    document.getElementById('otp-group').style.display = 'none';
                    document.getElementById('submit-otp').style.display = 'none';
                    document.getElementById('new-password-group').style.display = 'block';
                    document.getElementById('confirm-password-group').style.display = 'block';
                    document.getElementById('submit-password').style.display = 'block';
                } else {
                    alert("Sai mã xác thực, vui lòng thử lại.");
                }
            } catch (error) {
                alert("Có lỗi khi kiểm tra mã xác thực.");
                console.error(error);
            }
        });

        document.getElementById('submit-password').addEventListener('click', async function(event) {
            event.preventDefault();
            const email = document.getElementById('phone-email-password').value.trim();
            const password = document.getElementById('password').value.trim();
            const password1 = document.getElementById('password1').value.trim();

            if (!password || !password1) {
                alert("Vui lòng nhập đầy đủ mật khẩu mới.");
                return;
            }
            if (password !== password1) {
                alert("Hai mật khẩu phải giống nhau.");
                return;
            }

            try {
                const res = await fetch('reset_password_db.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        email,
                        new_password: password
                    })
                });
                const data = await res.json();
                if (data.success) {
                    showToast("Đổi mật khẩu thành công!");
                    // Có thể chuyển hướng về trang đăng nhập sau vài giây
                    setTimeout(() => {
                        window.location.href = "/e-web/user/page/sign-in/login2.php";
                    }, 2000);
                } else {
                    alert(data.message || "Có lỗi khi đổi mật khẩu.");
                }
            } catch (error) {
                alert("Có lỗi khi kết nối đến hệ thống.");
                console.error(error);
            }
        });

        function showToast(message, duration = 3001) {
            const toast = document.getElementById('success-toast');
            const toastMessage = document.getElementById('toast-message');
            toastMessage.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.style.display = 'none';
                    toast.classList.remove('hide');
                }, 500);
            }, duration);
        }
    </script>
</body>

</html>