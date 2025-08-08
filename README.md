Para levantar los servicios con docker puedes ejecutar:

```sh
docker compose up -d
```

acto seguido debes ejecutar lo siguiente:

```sh
./scripts.sh
```

###### esto copia el .env.example y genera el `APP_KEY`

Si tienes algun problema con el `./scripts.sh`, puedes ejecutar su contenido manualmente:

```sh
docker compose exec -it backend cp .env.example .env
docker compose exec -it backend php artisan key:generate
docker compose exec -it backend php artisan migrate --seed
```

Para ejecutar los test

```sh
docker compose exec -it backend php artisan test --coverage --min=80
```