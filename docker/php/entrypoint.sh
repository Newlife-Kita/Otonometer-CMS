#!/bin/sh
set -e

# Ensure storage dirs exist
mkdir -p /var/www/storage/framework/{sessions,views,cache}
mkdir -p /var/www/storage/logs
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/storage/logs

# Install composer dependencies (only if vendor/ is missing or outdated)
if [ ! -d "/var/www/vendor" ]; then
    echo ">> Running composer install"
    composer install --no-interaction --prefer-dist --optimize-autoloader
else
    echo ">> Skipping composer install (vendor already exists)"
fi

# Run migrations if needed (optional)
# php artisan migrate --force

# Finally run php-fpm
exec "$@"
