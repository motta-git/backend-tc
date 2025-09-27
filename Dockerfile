# Use an official PHP 8.3 image as a base
FROM php:8.3-fpm-alpine

# Install system dependencies needed for Laravel
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    sqlite-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite zip gd

# Install Composer (the PHP package manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your application files into the container
COPY . .

# Install your project's dependencies
RUN composer install --no-dev --optimize-autoloader

RUN php artisan migrate --force

RUN php artisan storage:link

# Set the correct permissions for Laravel's storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose the port that Laravel will run on
EXPOSE 8000

# This is the command that will run when the container starts
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]