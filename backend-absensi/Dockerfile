FROM dunglas/frankenphp:1.3-php8.3

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install bcmath gd pcntl zip pdo_pgsql

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy aplikasi
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Berikan izin ke folder storage
RUN chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 8000
EXPOSE 443
EXPOSE 80

# File entrypoint untuk menjalankan Octane/Queue
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
