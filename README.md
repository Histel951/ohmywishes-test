## Запуск + сборка
```shell
docker compose up -d --build
```

## Composer + миграции
```shell
docker compose exec php composer install
```
```shell
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

## Тесты
```shell
docker compose exec php vendor/bin/phpunit
```

## Curl эндпоинта
```shell
curl --location 'http://localhost:8080/wishlists/1/items' \
--header 'Content-Type: application/json' \
--data '{
    "productId": 14
}'
```