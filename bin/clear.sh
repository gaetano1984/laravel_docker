#!/bin/sh

docker container stop php webserver mysql
docker container rm -v php webserver mysql
docker image rm laravel_docker-mysql laravel_docker-php