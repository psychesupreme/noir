#!/bin/sh
# LF LINE ENDINGS FOR LINUX COMPATIBILITY (CACHE BUSTER DUMMY COMMENT)
set -e

# Ensure storage directories exist (persistent volume mounts as empty on first deploy)
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/testing
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/database

# Set permissions
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Create SQLite database if it doesn't exist
if [ "$DB_CONNECTION" = "sqlite" ] && [ ! -f "$DB_DATABASE" ]; then
    echo "Creating SQLite database at $DB_DATABASE..."
    touch "$DB_DATABASE"
    chown www-data:www-data "$DB_DATABASE"
    chmod 664 "$DB_DATABASE"
fi

# Create storage symlink
php /var/www/html/artisan storage:link --force 2>/dev/null || true

# Cache configuration, routes, and views for production
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache
php /var/www/html/artisan event:cache

# Execute supervisor or passed command
if [ $# -gt 0 ]; then
    echo "Executing command: $@"
    exec "$@"
else
    # Run database migrations
    if [ "$RUN_MIGRATIONS" = "true" ]; then
        echo "Running migrations..."
        php /var/www/html/artisan migrate --force
    fi
    echo "Starting Supervisord..."
    exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi
