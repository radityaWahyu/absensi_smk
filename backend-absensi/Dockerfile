FROM dunglas/frankenphp:1.1-php8.3-bookworm AS builder

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install pdo_pgsql zip pcntl opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Final Image
FROM dunglas/frankenphp:1.1-php8.3-bookworm

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql pcntl opcache

WORKDIR /app
COPY --from=builder /app /app

# Konfigurasi Octane & FrankenPHP
ENV AUTOCONF_PROGRAM=frankenphp
ENV LARAVEL_OCTANE=1
ENV APP_ENV=production

# Expose port
EXPOSE 80 443 443/udp

# Jalankan entrypoint (bisa dicustom via docker-compose)
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80"]
