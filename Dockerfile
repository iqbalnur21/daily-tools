FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan CI4
RUN apt-get update && apt-get install -y libicu-dev zip unzip libfreetype6-dev libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql intl gd

# Aktifkan mod_rewrite untuk CI4
RUN a2enmod rewrite

# Copy project ke direktori Apache
COPY . /var/www/html/

# Salin env.example ke .env
RUN cp /var/www/html/.env.example /var/www/html/.env

# Ganti DocumentRoot ke folder public (CI4)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Ubah konfigurasi Apache agar menggunakan public/
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Set permission (opsional)
RUN chown -R www-data:www-data /var/www/html
