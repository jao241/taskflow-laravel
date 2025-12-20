FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    curl \
    git

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

RUN pecl install xdebug && docker-php-ext-enable xdebug;

WORKDIR /var/www/html
COPY . .

RUN apt-get update && apt-get install -y unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

CMD ["php-fpm"]
