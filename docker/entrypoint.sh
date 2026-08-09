#!/bin/sh

# Cache configurations for speed in production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

# Seed the database (idempotent, safe to run multiple times)
echo "Running seeders..."
php artisan db:seed --force

# Start PHP-FPM in background
echo "Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx..."
nginx -g "daemon off;"
