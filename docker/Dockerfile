# Dockerfile
FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --prefer-dist

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
