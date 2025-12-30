# Dockerfile
FROM php:8.2-apache

# Gerekli PHP eklentilerini ve araçları kur
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    jpegoptim optipng pngquant gifsicle \
    vim \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

# Composer'ı kur (Multi-stage build ile daha verimli)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Apache mod_rewrite'ı etkinleştir
RUN a2enmod rewrite

# Apache sanal host dosyasını kopyala
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Çalışma dizinini ayarla
WORKDIR /var/www/html
