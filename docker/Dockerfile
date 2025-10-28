FROM php:8.2-cli

# Установка зависимостей
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    curl \
    npm \
    && docker-php-ext-install pdo pdo_pgsql

# Composer (уже установлен, но на всякий случай)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
