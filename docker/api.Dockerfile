
FROM php:8.2-fpm-alpine
RUN apk add --no-cache bash curl && docker-php-ext-install pdo pdo_pgsql opcache
WORKDIR /app
COPY . /app
EXPOSE 8080
CMD ["php","-S","0.0.0.0:8080","-t","public"]
