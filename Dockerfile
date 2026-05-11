FROM php:8.2-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
# Tăng giới hạn upload lên 1GB
COPY php.ini /usr/local/etc/php/conf.d/custom.ini
# Đảm bảo folder ảnh tồn tại và có quyền ghi để thêm sản phẩm thành công
RUN mkdir -p /var/www/html/assets/images && chmod -R 777 /var/www/html/assets/images || true
COPY . /var/www/html/
