# Stage 1: Build front-end assets
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Composer dependencies
FROM composer:2.7 AS composer-builder
WORKDIR /app
COPY composer*.json ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
COPY . .
RUN composer dump-autoload --no-dev --optimize

# Stage 3: Runner stage
FROM php:8.3-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    bash \
    sqlite-dev \
    mysql-client \
    postgresql-dev \
    icu-dev

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql pdo_sqlite pdo_pgsql pgsql zip opcache intl

# Setup working directory
WORKDIR /var/www/html

# Create system user www-data if it doesn't exist, and configure nginx directories
RUN adduser -D -S -G www-data www-data || true
RUN mkdir -p /var/log/nginx /var/lib/nginx /var/tmp/nginx /var/log/supervisor \
    && chown -R www-data:www-data /var/log/nginx /var/lib/nginx /var/tmp/nginx /var/log/supervisor

# Copy project files from builder stages
COPY --from=composer-builder --chown=www-data:www-data /app /var/www/html
COPY --from=node-builder --chown=www-data:www-data /app/public/build /var/www/html/public/build

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/fpm-pool-overrides.conf /usr/local/etc/php-fpm.d/zz-custom-limits.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

# Re-configure permissions for storage/bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
