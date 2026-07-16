#!/bin/bash

# Clear any cached configuration so it reads fresh Render env variables
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Run migrations automatically
php artisan migrate --force

# Start apache in the foreground
apache2-foreground