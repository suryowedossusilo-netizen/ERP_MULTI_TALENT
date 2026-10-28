FROM php:8.3-fpm-alpine

RUN apk add --no-cache nginx supervisor bash icu-libs libzip oniguruma \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring intl zip opcache bcmath \
    && apk del .build-deps \
    && cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && mkdir -p /var/lib/nginx/tmp/client_body /var/lib/nginx/tmp/fastcgi /var/lib/nginx/tmp/proxy /var/lib/nginx/tmp/uwsgi /var/lib/nginx/tmp/scgi \
    && chown -R nginx:nginx /var/lib/nginx

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html
COPY . .

RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/supervisord.conf /etc/supervisord.conf

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    COMPOSER_ALLOW_SUPERUSER=1

EXPOSE 10000

CMD ["/bin/sh", "/var/www/html/docker/start.sh"]
