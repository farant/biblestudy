FROM php:8.3-fpm-alpine

# Install PostgreSQL PDO driver, GD for image resizing, and nginx
RUN apk add --no-cache nginx libpq-dev freetype-dev libjpeg-turbo-dev libpng-dev libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_pgsql gd

# Copy nginx config
COPY nginx.conf /etc/nginx/http.d/default.conf

# Copy application code
COPY . /var/www/html
WORKDIR /var/www/html

# Create uploads directory on persistent volume with proper permissions
RUN mkdir -p /railway-volume/uploads \
    && chown -R www-data:www-data /railway-volume

# Start script: run migrations, then start nginx + php-fpm
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
