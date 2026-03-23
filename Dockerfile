FROM php:8.3-fpm-alpine

RUN apk add --no-cache bash git zip unzip postgresql-dev libpq-dev     && docker-php-ext-install pdo_pgsql opcache

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . .

CMD ["bash", "-c", "composer install && php bin/console doctrine:migrations:migrate --no-interaction && php-fpm"]
