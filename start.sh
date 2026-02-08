#!/bin/sh
set -e

echo "=== Checking volume mount ==="
if [ -d /railway-volume ]; then
    mkdir -p /railway-volume/uploads
    chown -R www-data:www-data /railway-volume/uploads
    if [ -f /railway-volume/.marker ]; then
        echo "Volume is persistent (marker file found from previous deploy)"
    else
        echo "First boot on this volume, creating marker file"
        date > /railway-volume/.marker
    fi
    echo "Uploads on volume: $(ls /railway-volume/uploads | wc -l) files"
else
    echo "WARNING: /railway-volume not found — uploads will NOT persist between deploys!"
fi

echo "=== Running database migrations ==="
php /var/www/html/migrations/migrate.php

echo "=== Starting PHP-FPM ==="
php-fpm -D

echo "=== Starting Nginx ==="
nginx -g 'daemon off;'
