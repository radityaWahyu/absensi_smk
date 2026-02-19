#!/bin/sh

# Menjalankan migrasi database (opsional, hati-hati di production)
# php artisan migrate --force

# Menjalankan Laravel Octane dengan FrankenPHP
php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=8000
