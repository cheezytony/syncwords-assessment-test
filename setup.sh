#! /bin/bash
cp .env.example .env

composer install --ignore-platform-reqs

php artisan key:generate

php artisan migrate --seed

php artisan serve --port=8765
