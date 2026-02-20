# Gunakan image resmi FrankenPHP
FROM dunglas/frankenphp:latest

# Install dependency sistem
RUN apt-get update && apt-get install -y \
    nano \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# Install Redis extension
# RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy source code
# COPY . /app

# Install dependency Laravel
# RUN composer install --no-dev --optimize-autoloader

# Permission
RUN chown -R www-data:www-data /app

# Expose port
EXPOSE 8000


