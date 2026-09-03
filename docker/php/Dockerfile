FROM php:8.5-fpm-bookworm

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libicu-dev \
        libpq-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) \
        intl \
        pdo_pgsql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY php.ini /usr/local/etc/php/conf.d/app.ini

WORKDIR /var/www/html