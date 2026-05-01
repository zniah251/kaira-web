<?php
$plain_password = "0777572408"; // Thay bằng mật khẩu bạn muốn dùng
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
echo $hashed_password;
?>