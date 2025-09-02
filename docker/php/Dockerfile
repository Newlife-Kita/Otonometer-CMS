# Stage 1: Get the Composer binary
FROM composer:2 as composer_stage

# ---

# Stage 2: Build the final application image
FROM php:8.2-fpm-alpine

WORKDIR /var/www

# Install system dependencies
RUN apk update && apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    git

# Install core PHP extensions for Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql zip bcmath exif

# Copy Composer binary from the first stage
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

# Copy only composer files to leverage Docker's cache
COPY composer.json composer.lock ./

# Install dependencies without autoloader optimization
RUN composer install --no-interaction --no-dev --no-scripts --no-autoloader

# Copy the rest of the application source code
COPY . .

# Generate the optimized autoloader now that all files are present
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# Create the storage directory structure if it doesn't exist
RUN mkdir -p storage/framework/{sessions,views,cache}

# Set correct permissions for the web server user
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9001
CMD ["php-fpm"]