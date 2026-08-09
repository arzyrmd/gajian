#!/bin/sh

# Cache configurations for faster Laravel response times in production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor to keep nginx and php running
exec supervisord -c /etc/supervisor/conf.d/supervisor.conf
