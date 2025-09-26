# ---------- Build Stage ----------
FROM composer:2.6 AS build

WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# ---------- Production Stage ----------
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install system dependencies for PHP
RUN apk add --no-cache \
    bash \
    libpng libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    zip unzip \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy installed vendor from build stage
COPY --from=build /app /var/www

# Copy environment file
COPY .env.example .env

# Set permissions (Render requires write access to storage/cache)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose port
EXPOSE 8000

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
