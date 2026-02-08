FROM php:8.3-fpm-alpine

# Install PostgreSQL PDO driver and nginx
RUN apk add --no-cache nginx libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copy nginx config
COPY nginx.conf /etc/nginx/http.d/default.conf

# Copy application code
COPY . /var/www/html
WORKDIR /var/www/html

# Create uploads directory with proper permissions
RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads

# Start script: run migrations, then start nginx + php-fpm
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
