const express = require('express');
const nodemailer = require('nodemailer');
const cors = require('cors');
require('dotenv').config();
console.log("DEBUG EMAIL_USER:", process.env.EMAIL_USER);
console.log("DEBUG EMAIL_PASS:", process.env.EMAIL_PASS ? "(Đã có mật khẩu )" : "(Thiếu mật khẩu )");


const app = express();
app.use(cors());
app.use(express.json());

const { EMAIL_USER, EMAIL_PASS } = process.env;

app.post('/send-verification', async (req, res) => {
  const { email, verificationCode } = req.body;

  if (!email || !verificationCode) {
    return res.status(400).json({ error: 'Thiếu email hoặc mã xác thực' });
  }

  try {
    const transporter = nodemailer.createTransport({
      service: 'gmail',
      auth: {
        user: EMAIL_USER,
        pass: EMAIL_PASS
      }
    });

    const html = `
      <p>Kính chào quý khách,</p>
      <p>Kaira đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
      <p>Vui lòng sử dụng mã xác thực dưới đây để tạo mật khẩu mới.</p>
      <h2>${verificationCode}</h2>
      <p>Để hoàn tất, bạn hãy quay lại trang web và nhập mã xác thực.</p>
      <p style="margin-top:0; font-weight: bold;">Lưu ý quan trọng:</p>
        - Mã xác thực này sẽ hết hạn sau <strong>15 phút</strong>.<br>
        - Vì lý do bảo mật, vui lòng không chia sẻ mã này cho bất kỳ ai.<br>
        - Nếu bạn không phải là người yêu cầu thay đổi mật khẩu, xin vui lòng bỏ qua email này. Tài khoản của bạn vẫn được an toàn. <br>
      <p style="margin-top: 30px;">Nếu bạn gặp bất kỳ khó khăn nào, đừng ngần ngại liên hệ với chúng tôi qua hotline 0901 234 567 hoặc email contact@kairashop.com.</p>
      <p>Trân trọng,<br>Đội ngũ Kaira.</p>
    `;

    const info = await transporter.sendMail({
      from: `"KAIRA Support" <${EMAIL_USER}>`,
      to: email,
      subject: 'Mã xác thực đặt lại mật khẩu',
      html
    });

    console.log('Email đã gửi:', info.messageId);
    res.json({ success: true });
  } catch (err) {
    console.error('Lỗi gửi mail:', err.message);
    res.status(500).json({ success: false, message: 'Lỗi gửi mail', error: err.message });
  }
});

const PORT = 3001;
app.listen(PORT, () => {
  console.log(`Mailer API đang chạy tại http://localhost:${PORT}`);
});
