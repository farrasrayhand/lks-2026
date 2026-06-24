FROM php:8.4-fpm AS base

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    git \
    curl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath \
    gd \
    opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd -r appuser && useradd -r -g appuser -d /var/www appuser

FROM base AS build

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install --no-interaction --no-autoloader --no-scripts --prefer-dist

COPY . .

RUN rm -f .env \
    && composer dump-autoload --optimize --classmap-authoritative --no-scripts \
    && rm -rf bootstrap/cache/*.php

RUN chown -R appuser:appuser /var/www

FROM base AS production

WORKDIR /var/www

COPY --from=build /var/www /var/www
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chown -R appuser:appuser /var/www/storage /var/www/bootstrap/cache \
    && chmod +x /usr/local/bin/entrypoint.sh

USER appuser

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
