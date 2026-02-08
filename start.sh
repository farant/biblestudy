#!/bin/sh
set -e

echo "=== Running database migrations ==="
php /var/www/html/migrations/migrate.php

echo "=== Starting PHP-FPM ==="
php-fpm -D

echo "=== Starting Nginx ==="
nginx -g 'daemon off;'
