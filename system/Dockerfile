### Dockerfile
ARG NODE_VERSION
ARG COMPOSER_VERSION
ARG PHP_VERSION
ARG CADDY_VERSION

FROM composer:${COMPOSER_VERSION} AS backend_composer

FROM php:${PHP_VERSION}-fpm AS backend_php

### Building PHP kit
RUN apt-get update && \
    apt-get install -y --no-install-recommends libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev && \
    pecl install apcu && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql && \
    docker-php-ext-enable apcu pdo_pgsql sodium && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && mkdir -pm a=rwx /app/var

COPY ./system/backend/php-custom.ini /usr/local/etc/php/php.ini
COPY --from=backend_composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

### Composer dependencies
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --prefer-dist

FROM caddy:${CADDY_VERSION}-alpine AS server_caddy

COPY ./system/server/Caddyfile /etc/caddy/Caddyfile

WORKDIR /app

COPY --from=backend_php /app/public/ ./public