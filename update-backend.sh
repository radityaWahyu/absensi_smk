#!/bin/bash

echo "🚀 Starting deployment..."

cd /var/www/absensi/app || exit

echo "📥 Pulling latest code..."
git pull https://github.com/radityaWahyu/absensi_smk.git

echo "📦 Installing dependencies..."
docker exec absensi_app composer install --no-dev --optimize-autoloader

echo "🗄 Running migrations..."
docker exec absensi_app php artisan migrate --force

echo "⚡ Caching config..."
docker exec absensi_app php artisan config:cache
docker exec absensi_app php artisan route:cache
docker exec absensi_app php artisan view:cache

echo "🔄 Restarting queue worker..."
docker restart absensi_queue

echo "♻ Reloading Octane..."
docker exec absensi_app php artisan octane:reload

echo "✅ Deployment finished!"