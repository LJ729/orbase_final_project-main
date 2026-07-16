# --------------------------------------------------
# Stage 1: Build frontend assets using Node
# --------------------------------------------------
FROM node:20-alpine AS frontend

WORKDIR /app

# Copy dependency definitions
COPY package.json package-lock.json ./

# Install clean dependencies
RUN npm ci

# Copy ALL necessary source files for Vite to run compilation
COPY . .

# Run the production build (Vite compiles files into public/build)
RUN npm run build

# Verify that Vite generated the manifest so the next stage doesn't crash
RUN test -f public/build/manifest.json \
    && echo "Frontend build completed successfully"

# --------------------------------------------------
# Stage 2: Laravel PHP and Apache
# --------------------------------------------------
FROM php:8.4-apache

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1 \
    COMPOSER_PROCESS_TIMEOUT=2000 \
    APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        pdo_pgsql \
        sockets \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite headers \
    && printf 'ServerName localhost\n' > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

# Pass Render's environment variables down to Apache so PHP can read them
RUN echo "PassEnv DATABASE_URL" >> /etc/apache2/apache2.conf \
    && echo "PassEnv DB_CONNECTION" >> /etc/apache2/apache2.conf \
    && echo "PassEnv APP_KEY" >> /etc/apache2/apache2.conf \
    && echo "PassEnv APP_ENV" >> /etc/apache2/apache2.conf \
    && echo "PassEnv APP_DEBUG" >> /etc/apache2/apache2.conf \
    && echo "PassEnv LOG_CHANNEL" >> /etc/apache2/apache2.conf \
    && echo "PassEnv SESSION_DRIVER" >> /etc/apache2/apache2.conf

# Pin DocumentRoot to Laravel's public/
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN sed -ri -e 's!AllowOverride None!AllowOverride All!g' \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --no-scripts

# Copy Laravel source code
COPY . .

# CRITICAL: Delete any local .env file copied from GitHub 
RUN rm -f /var/www/html/.env

# Remove any stale local Vite build and copy the production bundle
RUN rm -rf /var/www/html/public/build
COPY --from=frontend /app/public/build /var/www/html/public/build

# Verify manifest and matching asset files
RUN test -f /var/www/html/public/build/manifest.json \
    && echo "Vite files in final image:" \
    && find /var/www/html/public/build -maxdepth 2 -type f -print

# Rebuild autoloader and set up storage directories with proper ownership/permissions
RUN composer dump-autoload --optimize --no-scripts \
    && mkdir -p \
        storage/logs \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        bootstrap/cache \
    && touch storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/start.sh

EXPOSE 80

# This MUST be the last line of the Dockerfile
CMD ["./docker/start.sh"]