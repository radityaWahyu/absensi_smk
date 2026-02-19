FROM dunglas/frankenphp:1-php8.3-alpine AS builder

# Install system dependencies & PHP extensions
RUN install-php-extensions \
    pcntl \
    bcmath \
    gd \
    intl \
    pdo_pgsql \
    redis \
    zip \
    opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy source code
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# --- Production Image ---
FROM dunglas/frankenphp:1-php8.3-alpine

RUN install-php-extensions pdo_pgsql redis opcache pcntl

WORKDIR /app

# Copy dari builder
COPY --from=builder /app /app

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Konfigurasi FrankenPHP
ENV PHP_INI_SCAN_DIR=":/usr/local/etc/php/conf.d"
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-overrides.ini

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
