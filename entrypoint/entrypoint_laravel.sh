#!/bin/sh
set -e

cd /var/www/html/laravel && php artisan key:generate

php-fpm