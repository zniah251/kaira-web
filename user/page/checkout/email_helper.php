<?php
function sendOrderConfirmation($toEmail, $orderId, $amount) {
    $subject = "Xác nhận đơn hàng #$orderId từ KAIRA";
    $message = "
        <h2>Cảm ơn bạn đã đặt hàng tại KAIRA!</h2>
        <p>Đơn hàng <strong>#$orderId</strong> đã được thanh toán thành công.</p>
        <p>Số tiền: <strong>" . number_format($amount, 0, ',', '.') . "đ</strong></p>
        <p>Chúng tôi sẽ xử lý đơn hàng và giao đến bạn sớm nhất.</p>
        <p>Thân ái,<br>Đội ngũ KAIRA</p>
    ";

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Kaira Store <no-reply@kairashop.local>";

    mail($toEmail, $subject, $message, $headers);
}
