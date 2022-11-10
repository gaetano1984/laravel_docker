#!/bin/sh

docker-compose build --no-cache && docker-compose --env-file ./src/laravel/.env up -d