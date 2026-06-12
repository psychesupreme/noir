#!/bin/sh
set -e

# Cache configuration, routes, and views for production
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache
php /var/www/html/artisan event:cache

# Run database migrations
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running migrations..."
    php /var/www/html/artisan migrate --force
fi

# Execute supervisor
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
