FROM php:8.2-apache

# Cài extension mysqli cho PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy toàn bộ code vào container
COPY . /var/www/html/e-web/

# Cấu hình Apache
RUN echo "ServerName localhost" >> /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

# Phân quyền
RUN chown -R www-data:www-data /var/www/html/e-web/

EXPOSE 80