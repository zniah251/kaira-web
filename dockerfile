# Dockerfile
FROM php:8.2-apache

# Cài extension mysqli để kết nối MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Bật mod_rewrite của Apache (cần cho URL đẹp)
RUN a2enmod rewrite

# Copy toàn bộ source code vào Apache web root
COPY . /var/www/html/

# Cấp quyền cho thư mục upload ảnh
RUN mkdir -p /var/www/html/e-web/admin/assets/images \
    && chmod -R 777 /var/www/html/e-web/admin/assets/images

# Cấu hình Apache cho phép .htaccess
RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/allow-override.conf \
    && a2enconf allow-override

EXPOSE 80