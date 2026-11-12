FROM php:8.3-fpm

ENV PORT=8080

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        gettext-base \
        libcurl4-openssl-dev \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath curl dom gd intl mbstring mysqli pdo_mysql xml zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/wildlife

COPY public_html ./public_html
COPY assets ./assets
COPY includes ./includes
COPY config ./config

WORKDIR /var/www/wildlife/laravel

COPY laravel/composer.json laravel/composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-scripts \
    --no-autoloader

COPY laravel/app ./app
COPY laravel/bootstrap ./bootstrap
COPY laravel/config ./config
COPY laravel/public ./public
COPY laravel/resources ./resources
COPY laravel/routes ./routes
COPY laravel/storage ./storage
COPY laravel/artisan ./artisan

RUN composer dump-autoload --no-dev --no-interaction --no-scripts --optimize-autoloader \
    && mkdir -p bootstrap/cache \
    && php artisan package:discover --ansi \
    && mkdir -p \
        /var/www/wildlife/uploads \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
    && ln -s ../storage/app/public public/storage \
    && chown -R www-data:www-data \
        /var/www/wildlife/uploads \
        public/uploads \
        storage \
        bootstrap/cache

COPY nginx.conf /etc/nginx/nginx.conf.template

EXPOSE 8080

CMD ["sh", "-c", "php-fpm -D && envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf && exec nginx -g 'daemon off;'"]
