FROM php:8.2-apache

# Install extension PDO MySQL & mysqli yang dibutuhkan untuk koneksi database
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Aktifkan mod_rewrite Apache untuk fleksibilitas routing URL jika diperlukan
RUN a2enmod rewrite

# Salin source code aplikasi ke dalam container
COPY . /var/www/html/

# Buat folder uploads dan berikan izin tulis (write permission) ke user Apache (www-data)
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 775 /var/www/html/uploads

# Tentukan port default
EXPOSE 80
