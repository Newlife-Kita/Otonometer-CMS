FROM composer:2 as composer_stage

FROM php:8.2-fpm-alpine

WORKDIR /var/www

# Install system dependencies
RUN apk update && apk add --no-cache \
    libzip-dev \
    zip unzip \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    git bash

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip bcmath exif

# Copy Composer
COPY --from=composer_stage /usr/bin/composer /usr/bin/composer

# Copy entrypoint script
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy app source
COPY . .

EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
