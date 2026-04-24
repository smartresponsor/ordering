Дополнения к монорепозиторию:
- `Dockerfile` — образ PHP 8.3 с `amqp`, composer.
- `Makefile` — цели `up/down/test/worker/ci`.
- `api/openapi/order-v1.json` — текущая основная OpenAPI-спецификация для Order API.
- `api/openapi/archive/openapi-alpha-root.json` — архивный ранний root-level OpenAPI snapshot.
- `api/openapi/archive/order-rc3-p1p2-openapi.yaml` — архивный RC3 P1/P2 OpenAPI snapshot.
- `OrderComponent.postman_collection.json` — коллекция Postman.
- `docs/examples.http` — примеры HTTPie.

Инструкция:
1) Импортируй Postman-коллекцию и укажи `{{baseUrl}}` (например, http://localhost).
2) Или используй `docs/examples.http` с REST Client (VS Code) / HTTPie.
3) Собери контейнер: `docker build -t order-component .`
