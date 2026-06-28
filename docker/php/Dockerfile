FROM php:8.4-fpm-alpine

WORKDIR /var/www/app

RUN apk add --no-cache \
    bash \
    icu-dev \
    postgresql-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install \
    intl \
    pdo \
    pdo_pgsql \
    zip \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock symfony.lock ./

RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist

COPY . .

RUN composer dump-autoload --optimize --no-dev

RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var \
    && chmod -R 775 var

CMD ["php-fpm"]
