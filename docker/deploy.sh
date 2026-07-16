#!/usr/bin/env bash

echo "Caching Laravel assets..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running migrations..."
# Removes the --force flag if you want to run it manually later
php artisan migrate --force 

# Start PHP-FPM and Nginx
php-fpm -D
nginx -g "daemon off;"