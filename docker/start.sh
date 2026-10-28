#!/bin/sh
set -eu

cd /var/www/html

if [ -z "${APP_KEY:-}" ]; then
    echo "ERROR: APP_KEY must be configured in the hosting environment." >&2
    exit 1
fi

# Render's generated secret is stable across deploys. Derive a valid 32-byte
# Laravel key from it unless the operator supplied a valid base64: key already.
case "$APP_KEY" in
    base64:*) ;;
    *) export APP_KEY="$(php -r '$key = getenv("APP_KEY"); echo "base64:" . base64_encode(hash("sha256", $key, true));')" ;;
esac

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Never run database migrations or seeders automatically during deployment.
php artisan config:cache --no-interaction

exec /usr/bin/supervisord -c /etc/supervisord.conf
