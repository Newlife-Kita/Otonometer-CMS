# Use the official PHP image as the base image
FROM php:8.1-apache

# Set the working directory
WORKDIR /var/www/webcore_app

# Copy the Laravel project files into the container
COPY . .

## View files
RUN ls -la

ARG CONTAINER_MODE=app

# Update package
RUN apt-get clean
RUN apt-get update -yqq


RUN apt-get install gnupg -yqq

RUN apt-get install git libzip-dev libcurl4-gnutls-dev libicu-dev libmcrypt-dev libvpx-dev libjpeg-dev libpng-dev libxpm-dev zlib1g-dev libfreetype6-dev libxml2-dev libexpat1-dev libbz2-dev libgmp3-dev unixodbc-dev libpq-dev libpcre3-dev libtidy-dev libonig-dev -yqq


# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring curl intl gd xml zip bz2 opcache exif

# Enable Apache rewrite module
RUN a2enmod rewrite

# Configure the virtual host for Laravel
RUN cp kubernetes/default.conf /etc/apache2/sites-enabled/000-default.conf

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies using Composer
RUN composer install

RUN chmod +x artisan

# Generate the Laravel application key
RUN php artisan key:generate

# migrate
RUN php artisan migrate || true

# seed
RUN php artisan db:seed || true

# Clear the application cache
RUN php artisan optimize:clear

# Link the storage directory
RUN php artisan storage:link

# Set permissions for Laravel storage and bootstrap cache directories
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
RUN chmod +x entrypoint.sh

COPY kubernetes/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Expose the port that Apache will use
EXPOSE 80

# Start the Apache server
ENTRYPOINT ["./entrypoint.sh"]
