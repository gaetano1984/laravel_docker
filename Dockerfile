FROM php:7.3-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# da qui in giù spostare nell'entrypoint
# RUN cd /var/www/html/laravel && composer install
# RUN ls -la /var/www/html

COPY ./src/laravel/app /www/html/laravel
COPY ./entrypoint/entrypoint_laravel.sh /usr/local/bin/entrypoint_laravel
RUN chmod +x /usr/local/bin/entrypoint_laravel