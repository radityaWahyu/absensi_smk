############################################
# 1️⃣ Stage: Node Builder (Build Vite Assets)
############################################
FROM node:20-alpine AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


############################################
# 2️⃣ Stage: Composer Builder
############################################
FROM composer:2 AS composer_builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

COPY . .
RUN composer dump-autoload --optimize


############################################
# 3️⃣ Stage: Production Image (FrankenPHP)
############################################
FROM dunglas/frankenphp:latest

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    curl \
    && docker-php-ext-install pdo_pgsql zip opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# Copy php.ini optimized
COPY docker/php.ini /usr/local/etc/php/php.ini

WORKDIR /app

# Copy Laravel app
COPY --from=composer_builder /app /app

# Copy built assets
COPY --from=node_builder /app/public/build /app/public/build

# Set permission
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Switch to non-root
USER www-data

EXPOSE 8000

HEALTHCHECK CMD curl --fail http://localhost:8000 || exit 1

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000", "--workers=auto"]
