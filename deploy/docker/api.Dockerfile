
FROM php:8.2-fpm-alpine
RUN apk add --no-cache bash curl && docker-php-ext-install pdo pdo_pgsql opcache
WORKDIR /app
COPY . /app
RUN mkdir -p var/cache var/log && chown -R www-data:www-data var
USER www-data
EXPOSE 8080
CMD ["php","-S","0.0.0.0:8080","-t","public"]
