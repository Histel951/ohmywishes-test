## Стек
- PHP 8.5
- Symfony 8
- PostgreSQL 16

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

После запуска в базе создастся 3 записи wishlist с id - 1,2,3. Сделано для удобства, чтобы при ревью не нужно было лезть руками в базу.

Для `wishlist_items` используется составной UNIQUE constraint - он гарантирует на уровне базы данных, что один и тот же товар не может быть добавлен в одну wishlist несколько раз.
Таким образом у нас происходит проверка на domain уровне и на уровне базы данных.

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

Пример успешного ответа (код 201):
```json
{
    "id": 3,
    "wishlistId": 1,
    "productId": 14
}
```

Пример ошибки при повторном запросе (код 409):
```json
{
    "error": "Product 14 already exists in wishlist 1."
}
```
## Doctrine маппинг
Doctrine маппинг вынесен в xml и находится в Infrastructure слое:

`src/Wishlist/Infrastructure/Persistence/Doctrine/Mapping/`

Я намеренно не использовал аттрибуты в Domain сущностях, чтобы Domain слой не зависел от Doctrine и оставался независимым от инфраструктуры.

Таким образом, Entity содержит только доменную логику, а детали хранения и маппинга находятся в Infrastructure слое.

## Архитектурные решения

- Domain сущности не зависят от Symfony и Doctrine.
- Маппинг вынесен в xml и находится в Infrastructure слое.
- Входные данные преобразуются в DTO до передачи в Application слой.
- Валидация входных данных выполняется отдельно от бизнес логики.
- Бизнес правила находятся в Domain сущностях.
- Целостность данных дополнительно обеспечивается ограничениями PostgreSQL.