#!/bin/sh

docker compose exec -it backend cp .env.example .env
docker compose exec -it backend php artisan key:generate
docker compose exec -it backend php artisan migrate --seed