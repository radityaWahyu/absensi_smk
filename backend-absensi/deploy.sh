#!/bin/bash

# Pastikan script berhenti jika ada error
set -e

echo "🚀 Memulai proses deployment..."

# 1. Tarik perubahan terbaru dari repository
echo "Step 1: Menarik kode terbaru dari Git..."
git pull https://github.com/radityaWahyu/absensi_smk.git

# 2. Build ulang image (hanya jika ada perubahan Dockerfile/code)
echo "Step 2: Membangun image Docker (No-Cache untuk keamanan)..."
docker compose build --no-cache api worker

# 3. Jalankan container dalam mode detached
echo "Step 3: Menjalankan container..."
docker compose up -d

# 4. Instalasi dependency PHP (di dalam container)
echo "Step 4: Mengoptimalkan Composer..."
docker exec laravel-api composer install --no-dev --optimize-autoloader

# 5. Jalankan migrasi database
echo "Step 5: Menjalankan migrasi database..."
# --force diperlukan untuk menjalankan migrasi di environment production
docker exec laravel-api php artisan migrate --force

# 6. Bersihkan dan optimalkan cache Laravel
echo "Step 6: Optimasi cache konfigurasi dan route..."
docker exec laravel-api php artisan config:cache
docker exec laravel-api php artisan route:cache
docker exec laravel-api php artisan view:cache

# 7. Restart Worker untuk memastikan kode terbaru dijalankan
echo "Step 7: Restarting Queue Worker..."
docker-compose restart worker

echo "✅ Deployment selesai! Aplikasi Anda kini online."
