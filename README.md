## Стек
Из по ТЗ:
- PHP: 8.3+
- MySQL: 8.0+
- Laravel 13
- PHPUnit
- Larastan / PHPStan
- Docker Compose (только для базы MySQL - мне так удобно)

## Запуск проекта
Для удобства сделал `docker-compose.yml` с MySQL и отдельной тестовой базой, чтобы локально не разворачивать инфраструктуру.
При желании можно использовать локально развёрнутую базу.

Запуск докер с MySQL:
```shell
docker compose up -d
```

Composer:
```shell
composer install
```

Мой полный локальный .env:
```dotenv
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:CENLy9KXuWh1+0kTpWdzIYZSDnkGbIRJ1IQn8679WyE=
APP_DEBUG=true
APP_URL=http://localhost:8000

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_API_KEY=api-key-123
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=helpdesk
DB_USERNAME=root
DB_PASSWORD=root

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

Миграции:
```shell
php artisan migrate
```

Сиды:
```shell
php artisan db:seed
```

Запуск проекта (без Docker):
```shell
php artisan serve
```

## API
### Создание тикета:
```shell
curl --location 'http://127.0.0.1:8000/api/v1/tickets' \
--header 'X-API-Key: api-key-123' \
--header 'Content-Type: application/json' \
--data-raw '{
    "title": "danil test",
    "description": "description description description description description description description description description ",
    "author_email": "example_email@gmail.com"
}'
```

Ответ 201:
```json
{
    "data": {
        "id": 17
    }
}
```


### Просмотр тикета
```shell
curl --location 'http://127.0.0.1:8000/api/v1/tickets/17' \
--header 'X-API-Key: api-key-123'
```

Ответ 200:
```json
{
    "data": {
        "id": 17,
        "title": "danil test",
        "description": "description description description description description description description description description",
        "author_email": "example_email@gmail.com",
        "status": "done",
        "version": 4,
        "created_at": "2026-09-24T12:08:26.000000Z",
        "updated_at": "2026-09-24T12:09:43.000000Z",
        "comments": [
            {
                "id": 29,
                "author": "danil",
                "message": "всё норм 3",
                "created_at": "2026-09-24T12:08:36.000000Z"
            },
            {
                "id": 30,
                "author": "danil",
                "message": "всё норм 4",
                "created_at": "2026-09-24T12:09:30.000000Z"
            },
            {
                "id": 31,
                "author": "danil",
                "message": "всё норм 5",
                "created_at": "2026-09-24T12:09:43.000000Z"
            }
        ]
    }
}
```

### Добавить комментарий / изменить статус
При добавлении комментария - `version` не увеличивается.
Всё выполняется атомарно, в одной транзакции. Есть проверка version (оптимистичная блокировка).
```shell
curl --location 'http://127.0.0.1:8000/api/v1/tickets/17/events' \
--header 'X-API-Key: api-key-123' \
--header 'Content-Type: application/json' \
--data '{
    "status": "done",
    "version": 3,
        "comment": {
        "author": "danil",
        "message": "всё норм 5"
    }
}'
```

Ответ 200:
```json
{
    "data": {
        "id": 17,
        "version": 4,
        "status": "done",
        "comment": {
            "author": "danil",
            "message": "всё норм 5"
        }
    }
}
```

Ответ 409 при конфликте версий:
```json
{
    "error": {
        "code": "version_conflict",
        "message": "Ticket version is outdated."
    }
}
```

Ответ 422, например при неправильно переданном статусе:
```json
{
    "error": {
        "code": "validation_error",
        "message": "The given data was invalid.",
        "details": {
            "status": [
                "The selected status is invalid."
            ]
        }
    }
}
```

### Поиск Tickets по query параметрам
```shell
curl --location 'http://127.0.0.1:8000/api/v1/tickets?per_page=5&page=2' \
--header 'X-API-Key: api-key-123'
```

Ответ 200:
```json
{
    "data": {
        "page": 2,
        "per_page": 5,
        "total": 17,
        "items": [
            {
                "id": 4,
                "title": "Magnam consequuntur suscipit vero laboriosam repudiandae.",
                "status": "new",
                "created_at": "2026-08-25 13:21:28",
                "updated_at": "2026-08-31 01:26:34"
            },
            {
                "id": 11,
                "title": "Qui dolores in voluptatem soluta.",
                "status": "done",
                "created_at": "2026-08-24 17:11:23",
                "updated_at": "2026-08-26 15:49:58"
            },
            {
                "id": 8,
                "title": "Quis delectus minus repudiandae qui.",
                "status": "in_progress",
                "created_at": "2026-08-22 04:22:42",
                "updated_at": "2026-09-18 10:19:30"
            },
            {
                "id": 7,
                "title": "Alias rerum sint similique aut iusto.",
                "status": "in_progress",
                "created_at": "2026-08-18 11:40:09",
                "updated_at": "2026-09-18 05:52:56"
            },
            {
                "id": 5,
                "title": "Porro veritatis ipsam laudantium dolorem eos consequatur.",
                "status": "new",
                "created_at": "2026-08-16 08:46:37",
                "updated_at": "2026-09-24 11:21:26"
            }
        ]
    }
}
```

Ответ 422, при неправильном параметре page (пример):
```json
{
    "error": {
        "code": "validation_error",
        "message": "The given data was invalid.",
        "details": {
            "page": [
                "The page field must be at least 1."
            ]
        }
    }
}
```

## Решения
- Работу с бд делал целенаправленно без использования ORM, поскольку обсуждали на собеседовании, что так будет предпочтительнее для тестового.
- Немного отошёл от формата ответа, закреплённого в ТЗ, а именно:
```json
{
    "data": {
        ...
    }
}
```
Поскольку использовал Laravel Resource, по стандарту они имеют вложенность с `data`.

- В проекте есть несколько Feature / Unit тестов, запуск:
```shell
composer test
```
- Прикрутил Larastan (PHPStan), запуск:
```shell
composer analyse
```

## Быстрая проверка
```shell
php artisan migrate
```
```shell
php artisan db:seed
```
```shell
composer test
```
```shell
composer analyse
```

После этого эндпоинты доступны для проверки с заголовками:
```
X-API-Key: api-key-123
```

## Использование AI агентов:
- Проверка и доработка `app/Repositories/Ticket/MysqlTicketRepository.php`, а именно метода `paginate`.
- Написание тестов
- Разбор ошибок после php stan анализа, тк я не помню все PHPDoc + вручную это довольно долго.

Весь код сгенерированный AI ревьювил и проверял вручную, перед пушем на гит.
