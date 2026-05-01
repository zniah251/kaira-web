# KAIRA SHOP - E-Commerce Platform

**KAIRA SHOP** là một nền tảng thương mại điện tử hiện đại được xây dựng bằng **PHP, MySQL, JavaScript** và các công nghệ web tiên tiến. Hệ thống cung cấp đầy đủ tính năng cho cửa hàng thời trang trực tuyến với giao diện thân thiện và tính năng quản lý mạnh mẽ.

---

## Mục Lục

1. [Giới Thiệu Tính Năng](#giới-thiệu-tính-năng)
2. [Yêu Cầu Hệ Thống](#yêu-cầu-hệ-thống)
3. [Cài Đặt & Thiết Lập](#cài-đặt--thiết-lập)
4. [Cấu Trúc Dự Án](#cấu-trúc-dự-án)
5. [Hướng Dẫn Sử Dụng](#hướng-dẫn-sử-dụng)
6. [Tài Khoản Demo](#tài-khoản-demo)
7. [Troubleshooting](#troubleshooting)

---

## Giới Thiệu Tính Năng

### **Phía Khách Hàng (User)**
- **Duyệt Sản Phẩm**: Danh mục phân loại (Nam, Nữ, Bộ sưu tập, On Sale)
- **Tìm Kiếm & Lọc**: Tìm sản phẩm theo tên, giá, rating
- **Chi Tiết Sản Phẩm**: Xem đầy đủ thông tin, hình ảnh, review, rating
- **Giỏ Hàng**: Thêm/xóa sản phẩm, chỉnh sửa số lượng, kích cỡ, màu sắc
- **Thanh Toán**: Hỗ trợ COD, MOMO, Bank Transfer, Credit Card, Smart Banking
- **Đơn Hàng**: Theo dõi trạng thái đơn hàng (Pending → Confirmed → Shipping → Delivered)
- **Tài Khoản**: Đăng ký, đăng nhập, quên mật khẩu, chỉnh sửa hồ sơ
- **Yêu Thích**: Thêm/xóa sản phẩm vào wishlist
- **Review & Đánh Giá**: Viết đánh giá, xem rating sản phẩm
- **Blog**: Đọc bài viết tin tức và hướng dẫn
- **Khuyến Mãi**: Sử dụng voucher giảm giá
- **Thông Tin**: FAQ, Chính sách, Tuyển dụng, Giới thiệu

### **Phía Quản Trị (Admin)**
- **Dashboard**: Thống kê bán hàng, doanh thu, đơn hàng
- **Quản Lý Sản Phẩm**: CRUD sản phẩm, upload hình ảnh, quản lý kho
- **Quản Lý Danh Mục**: Tạo/sửa/xóa danh mục sản phẩm
- **Quản Lý Đơn Hàng**: Xem, cập nhật trạng thái, tính năng giao hàng
- **Quản Lý Khách Hàng**: Xem thông tin, lịch sử đơn hàng
- **Quản Lý Blog**: Viết/sửa/xóa bài viết
- **Quản Lý Voucher**: Tạo mã giảm giá, thiết lập điều kiện áp dụng
- **Quản Lý Review**: Duyệt và xóa review không phù hợp

---

## 🔧 Yêu Cầu Hệ Thống

### **Phần Mềm Cần Thiết**

| Công Nghệ | Phiên Bản | Mục Đích |
|-----------|-----------|---------|
| **PHP** | 8.0+ | Server-side scripting |
| **MySQL** | 5.7+ hoặc **MariaDB** 10.4+ | Database management |
| **Node.js** | 14.0+ | Runtime cho JavaScript backend (email service) |
| **npm** | 6.0+ | Package manager cho Node.js |
| **Apache** | 2.4+ | Web server |

### **Công Cụ Khác**
- **Git** (tùy chọn): Để clone repository
- **Composer** (tùy chọn): Nếu có dependencies PHP
- **VS Code / PHPStorm**: Editor code
- **Postman / Insomnia**: Test API (tùy chọn)

### **Hệ Điều Hành Hỗ Trợ**
- Windows 10/11
- macOS 10.14+
- Linux (Ubuntu 18.04+, CentOS 7+)

---

## Cài Đặt & Thiết Lập

### **Bước 1: Cài Đặt Phần Mềm Cần Thiết**

#### **Trên Windows**

1. **PHP 8.0+**
   ```bash
   # Download từ https://windows.php.net/downloads/releases/
   # Hoặc sử dụng XAMPP (bao gồm PHP + Apache + MySQL)
   # Download XAMPP: https://www.apachefriends.org/
   
   # Cài đặt XAMPP: Next → Next → Next → Finish
   # Khởi động XAMPP Control Panel
   # Bật Apache và MySQL
   ```

2. **MySQL / MariaDB**
   ```bash
   # Nếu dùng XAMPP, MySQL đã được cài sẵn
   # Hoặc download riêng: https://dev.mysql.com/downloads/mysql/
   
   # Kiểm tra MySQL chạy:
   mysql --version
   ```

3. **Node.js & npm**
   ```bash
   # Download từ https://nodejs.org/
   # Chọn LTS version (dài hạn, ổn định)
   # Cài đặt: Next → Next → Next → Finish
   
   # Kiểm tra cài đặt:
   node --version
   npm --version
   ```

#### **Trên macOS**

```bash
# Cài đặt Homebrew (nếu chưa có)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Cài đặt PHP
brew install php@8.1

# Cài đặt MySQL
brew install mysql@5.7
brew services start mysql@5.7

# Cài đặt Node.js
brew install node

# Cài đặt Apache (macOS đã có sẵn, bạn có thể sử dụng)
# Hoặc dùng MAMP: https://www.mamp.info/
```

#### **Trên Linux (Ubuntu/Debian)**

```bash
# Cập nhật package manager
sudo apt update && sudo apt upgrade

# Cài đặt PHP & Extension
sudo apt install php php-mysql php-curl php-json php-mbstring

# Cài đặt Apache
sudo apt install apache2
sudo systemctl start apache2
sudo systemctl enable apache2

# Cài đặt MySQL
sudo apt install mysql-server
sudo mysql_secure_installation

# Cài đặt Node.js
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
sudo apt install nodejs
```

---

### **Bước 2: Lấy Source Code**

```bash
# Option 1: Clone từ Git (nếu có)
git clone https://github.com/zniah251/e-web.git
cd e-web

# Option 2: Download ZIP và giải nén
# - Download ZIP từ GitHub
# - Giải nén vào thư mục Apache
```

### **Bước 3: Đặt Thư Mục Dự Án**

**Trên Windows với XAMPP:**
```bash
# Sao chép folder e-web vào:
C:\xampp\htdocs\e-web

# Kiểm tra cấu trúc:
C:\xampp\htdocs\e-web\
├── admin/
├── user/
├── connect.php
├── footer.php
├── navbar.php
└── ...
```

**Trên Linux/macOS:**
```bash
# Đặt vào document root của Apache
sudo cp -r e-web /var/www/html/e-web
sudo chown -R www-data:www-data /var/www/html/e-web
```

---

### **Bước 4: Cài Đặt Database**

1. **Tạo Database**
```bash
# Mở terminal/command prompt

# Đăng nhập MySQL
mysql -u root -p

# Nhập password (XAMPP mặc định: không có password, nhấn Enter)
# Hoặc password bạn đã đặt

# Tạo database
CREATE DATABASE `e-web` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `e-web`;

# Nếu muốn xóa database cũ trước
DROP DATABASE IF EXISTS `e-web`;
CREATE DATABASE `e-web` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. **Import SQL Files**
```bash
# Từ terminal/command prompt:
mysql -u root -p e-web < C:\path\to\e-web\e-web.sql
mysql -u root -p e-web < C:\path\to\e-web\product.sql

# Hoặc sử dụng phpMyAdmin:
# - Mở http://localhost/phpmyadmin
# - Chọn database e-web
# - Tab Import → Chọn file e-web.sql → Go
# - Lặp lại với product.sql
```

3. **Kiểm Tra Database**
```bash
mysql -u root -p e-web -e "SHOW TABLES;"

# Kết quả sẽ hiển thị các bảng:
# blog, cart, category, galery, message, orders, order_detail, product, ...
```

---

### **Bước 5: Cấu Hình File Kết Nối**

1. **Mở file `connect.php`**
```php
<?php
$servername = "localhost";      // MySQL server
$username = "root";              // MySQL username
$password = "";                  // MySQL password (XAMPP mặc định trống)
$dbname = "e-web";              // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cài đặt charset
$conn->set_charset("utf8mb4");
?>
```

2. **Điều Chỉnh Nếu Cần**
```php
// Nếu MySQL có password:
$password = "your_password";

// Nếu MySQL chạy trên port khác:
$servername = "localhost:3307";

// Nếu sử dụng MariaDB:
$servername = "localhost"; // Vẫn giống nhau
```

---

### **Bước 6: Cài Đặt Dependencies Node.js**

```bash
# Chuyển vào thư mục dự án
cd C:\xampp\htdocs\e-web

# Hoặc trên Linux/macOS
cd /var/www/html/e-web

# Cài đặt dependencies
npm install

# Kiểm tra packages được cài:
npm list
```

**Kết quả sẽ cài các packages:**
- `cors` - CORS middleware
- `dotenv` - Environment variables
- `express` - Web framework
- `mysql2` - MySQL driver
- `nodemailer` - Email service

---

### **Bước 7: Cấu Hình Environment (nếu cần email)**

1. **Tạo file `.env` trong `user/page/sign-in/`**
```bash
# Trên Windows
cd C:\xampp\htdocs\e-web\user\page\sign-in
echo. > .env

# Hoặc tạo file bằng editor, thêm nội dung:
EMAIL_USER=your_email@gmail.com
EMAIL_PASS=your_app_password
```

2. **Lấy App Password từ Gmail**
   - Đăng nhập: https://myaccount.google.com
   - Security → App Passwords
   - Chọn: Mail & Windows
   - Sao chép password vào `.env`

---

### **Bước 8: Khởi Động Website**

**Phương Pháp 1: XAMPP (Windows)**
```bash
# Mở XAMPP Control Panel
# Bật Apache (nút Start)
# Bật MySQL (nút Start)

# Mở browser
# Truy cập: http://localhost/e-web
```

**Phương Pháp 2: PHP Built-in Server**
```bash
# Mở terminal, chuyển vào thư mục dự án
cd C:\xampp\htdocs\e-web

# Khởi động server
php -S localhost:8000

# Truy cập: http://localhost:8000
```

**Phương Pháp 3: Apache (Linux/macOS)**
```bash
# Khởi động Apache
sudo systemctl start apache2

# Truy cập: http://localhost/e-web
```

---

### **Bước 9: Kiểm Tra Hoạt Động**

Mở browser và truy cập:

| URL | Mục Đích |
|-----|---------|
| `http://localhost/e-web` | Trang chủ khách hàng |
| `http://localhost/e-web/admin` | Trang admin |
| `http://localhost/phpmyadmin` | Quản lý database |

---

## Cấu Trúc Dự Án

```
e-web/
│
├── admin/                          # Phía quản trị
│   ├── pages/
│   │   ├── add-products/          # Thêm sản phẩm
│   │   ├── blog/                  # Quản lý blog
│   │   ├── category-list/         # Danh mục sản phẩm
│   │   ├── customers/             # Khách hàng
│   │   ├── dashboard/             # Bảng điều khiển
│   │   ├── orderdetails/          # Chi tiết đơn hàng
│   │   ├── orderlist/             # Danh sách đơn hàng
│   │   └── produclist/            # Danh sách sản phẩm
│   ├── template/                  # Giao diện admin
│   │   ├── assets/
│   │   ├── pages/
│   │   └── index.php
│   └── documentation/
│
├── user/                           # Phía khách hàng
│   ├── page/
│   │   ├── aboutus/               # Giới thiệu
│   │   ├── ajax_handlers/         # AJAX endpoints
│   │   ├── blog/                  # Blog
│   │   ├── cart/                  # Giỏ hàng
│   │   ├── checkout/              # Thanh toán
│   │   ├── collection/            # Bộ sưu tập
│   │   ├── faq/                   # Hỏi đáp
│   │   ├── member/                # Thành viên
│   │   ├── onsale/                # Giảm giá
│   │   ├── product_detail/        # Chi tiết sản phẩm
│   │   ├── sign-in/               # Đăng nhập/Đăng ký
│   │   ├── users/                 # Hồ sơ người dùng
│   │   ├── man/                   # Sản phẩm nam
│   │   └── woman/                 # Sản phẩm nữ
│   ├── css/                        # CSS utilities
│   ├── js/                         # JavaScript
│   ├── images/                     # Hình ảnh
│   ├── icon/                       # Icon
│   └── index.php
│
├── connect.php                     # Kết nối database
├── footer.php                      # Footer chung
├── navbar.php                      # Navbar chung
├── package.json                    # Dependencies Node.js
├── e-web.sql                       # SQL dump main
├── product.sql                     # SQL dump products
└── README.md                       # File này
```

---

## 💻 Hướng Dẫn Sử Dụng

### **Khách Hàng**

1. **Duyệt Sản Phẩm**
   - Vào `http://localhost/e-web`
   - Chọn danh mục (Nam, Nữ, Bộ sưu tập, Giảm giá)
   - Sắp xếp theo giá, rating

2. **Xem Chi Tiết Sản Phẩm**
   - Click vào sản phẩm
   - Xem hình ảnh, mô tả, giá
   - Chọn kích cỡ, màu sắc
   - Đọc review từ khách hàng khác

3. **Thêm Vào Giỏ Hàng**
   - Click "Thêm vào giỏ"
   - Chỉnh sửa số lượng
   - Tiếp tục mua sắm hoặc thanh toán

4. **Thanh Toán**
   - Vào Giỏ hàng
   - Nhập thông tin giao hàng
   - Chọn phương thức thanh toán (COD, MOMO, Bank, ...)
   - Hoàn thành đơn hàng

5. **Theo Dõi Đơn Hàng**
   - Đăng nhập vào tài khoản
   - Vào "Đơn hàng của tôi"
   - Xem trạng thái: Pending → Confirmed → Shipping → Delivered

### **Quản Trị Viên**

1. **Đăng Nhập Admin**
   - Vào `http://localhost/e-web/admin`
   - Đăng nhập bằng tài khoản admin (xem bên dưới)
   - Truy cập dashboard

2. **Quản Lý Sản Phẩm**
   - Sidebar → Products → Add Product
   - Nhập tên, giá, mô tả
   - Upload hình ảnh
   - Lưu sản phẩm

3. **Quản Lý Đơn Hàng**
   - Sidebar → Orders
   - Xem danh sách đơn hàng
   - Click để xem chi tiết
   - Cập nhật trạng thái (Confirmed, Shipping, Delivered)

4. **Quản Lý Khách Hàng**
   - Sidebar → Customers
   - Xem danh sách khách hàng
   - Xem lịch sử đơn hàng

5. **Quản Lý Danh Mục**
   - Sidebar → Categories
   - Thêm/sửa/xóa danh mục

---

## 🔑 Tài Khoản Demo

### **Admin (Quản Trị)**
| Thông Tin | Giá Trị |
|-----------|--------|
| **URL** | `http://localhost/e-web/admin` |
| **Email** | Sẽ được cấp trong database hoặc tạo thủ công |
| **Password** | Sẽ được cấp trong database hoặc tạo thủ công |

**Để tạo admin account thủ công:**
```sql
-- Mở database e-web

-- Kiểm tra role
SELECT * FROM role;
-- rid=1 là admin

-- Tạo admin (password hash = "123456")
INSERT INTO users (uname, email, phonenumber, address, password, rid) 
VALUES ('Admin', 'admin@kairashop.com', '0901234567', '123 Đường XYZ', '$2y$10$...hashed_password...', 1);

-- Hoặc sử dụng password thường text (không an toàn - chỉ dùng test):
INSERT INTO users (uname, email, phonenumber, address, password, rid) 
VALUES ('Admin', 'admin@kairashop.com', '0901234567', '123 Đường XYZ', 'admin123', 1);
```

### **User (Khách Hàng)**
| Thông Tin | Giá Trị |
|-----------|--------|
| **URL** | `http://localhost/e-web` |
| **Đăng Ký** | Bấm "Đăng ký" trên trang chủ |
| **Đăng Nhập** | Sử dụng tài khoản vừa tạo |

---

## 🐛 Troubleshooting

### **Lỗi: "Connection refused" hoặc "Cannot connect to database"**

**Nguyên Nhân:**
- MySQL chưa chạy
- Tên user/password sai
- Database không tồn tại

**Giải Pháp:**
```bash
# 1. Kiểm tra MySQL có chạy
ps aux | grep mysql          # Linux/macOS
tasklist | findstr mysql     # Windows

# 2. Khởi động MySQL (XAMPP)
# - Mở XAMPP Control Panel
# - Bật MySQL

# 3. Kiểm tra connection
mysql -u root -p -h localhost
# Nhập password rồi nhấn Enter

# 4. Kiểm tra database tồn tại
SHOW DATABASES;
# Phải thấy "e-web" trong danh sách
```

### **Lỗi: "Class 'mysqli' not found"**

**Nguyên Nhân:**
- PHP extensions chưa kích hoạt

**Giải Pháp:**
```bash
# 1. Tìm file php.ini
# Windows: C:\xampp\php\php.ini
# Linux: /etc/php/8.1/apache2/php.ini

# 2. Bỏ comment các dòng:
extension=mysqli
extension=pdo_mysql

# 3. Khởi động lại Apache
```

### **Lỗi: "Class 'PDO' not found"**

**Giải Pháp:**
```bash
# 1. Mở php.ini
# 2. Thêm hoặc bỏ comment:
extension=pdo
extension=pdo_mysql

# 3. Khởi động lại Apache
```

### **Lỗi: "npm install" không chạy**

**Giải Pháp:**
```bash
# 1. Kiểm tra Node.js cài đúng
node --version
npm --version

# 2. Xóa node_modules cũ
rm -rf node_modules
rm package-lock.json

# 3. Cài lại
npm install

# 4. Nếu lỗi permission trên Linux
sudo npm install
```

### **Lỗi: "Port 80 đã được sử dụng" (Apache)**

**Giải Pháp:**
```bash
# 1. Sử dụng port khác
php -S localhost:8080

# 2. Hoặc dừng service chiếm dụng port 80
# Windows: 
netstat -ano | findstr :80
taskkill /PID <PID> /F

# Linux:
sudo lsof -i :80
sudo kill -9 <PID>
```

### **Lỗi: "No such file or directory" khi import SQL**

**Giải Pháp:**
```bash
# 1. Kiểm tra đường dẫn file
ls -la e-web.sql          # Linux/macOS
dir e-web.sql             # Windows

# 2. Sử dụng đường dẫn tuyệt đối
mysql -u root -p e-web < /full/path/to/e-web.sql

# 3. Hoặc sử dụng phpMyAdmin thay vì command line
```

### **Lỗi: "Charset utf8mb4 không hỗ trợ"**

**Giải Pháp:**
```sql
-- Sửa database charset
ALTER DATABASE e-web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sửa từng table
ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE product CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- ... repeat for all tables
```

---

## Ghi Chú Quan Trọng

**SECURITY WARNINGS:**
- Không commit file `.env` vào Git (chứa sensitive data)
- Sử dụng password hashed trong production
- Xóa debug code và logs trước khi deploy
- Cấu hình SSL/HTTPS cho production
- Kiểm tra SQL Injection, XSS vulnerabilities

**BEST PRACTICES:**
- Sao lưu database thường xuyên
- Kiểm tra file permissions (755 cho folders, 644 cho files)
- Sử dụng prepared statements cho queries
- Validate input từ user
- Sử dụng CORS headers đúng cách
- Rate limiting cho API
---

**Happy Coding! **

Nếu gặp vấn đề, vui lòng kiểm tra lại các bước cài đặt hoặc liên hệ với nhóm phát triển.
