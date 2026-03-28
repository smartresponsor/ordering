# Changelog

## Unreleased
- Приложение переведено на единый Symfony-root namespace `App\\ => src/` с рабочим активным runtime-слоем без альтернативных root namespace.
- Активное дерево выровнено под Symfony-oriented слои; конфликтующие legacy-код, legacy-конфигурации и старые шаблоны вынесены из рабочего runtime в архивные директории.
- Сервисный слой приведен к симметрии `src/Service/...` и `src/ServiceInterface/...`; компонентные сервисы переименованы в `Order*Service`, а DI-алиасы и autowiring обновлены.
- Добавлены DTO, `OrderStatus` value object, обновленная модель заказа и связанные сущности платежей, возвратов и отгрузок без DQL и с валидным Doctrine mapping.
- Собран demo-management UI на Twig, Bootstrap, Symfony Form и Validator для создания заказов, оплат, возвратов и отгрузок в существующем HTTP flow.
- Добавлены Fixtures и Faker-based demo flow, новые CLI-команды диагностики, загрузки demo-данных, сброса и отчетности.
- Выровнены `config/`, `.env`, `.env.test`, DI container wiring, Twig/Form/Validator/Doctrine/Security configuration и локальный SQLite-first runtime.
- Добавлены unit, integration, functional, Panther и Playwright тесты для ключевых пользовательских сценариев компонента.
- Добавлен локальный quality gate `pipeline:local:full` с container/yaml/twig/schema lint, audit и тестовыми шагами.

## v0.4.0-beta (2025-10-06)
- Webhooks: payment/refund с идемпотентностью (Idempotency-Key).
- ReadModel: сервис пересчёта `OrderReadModelUpdater` + команда `order:readmodel:sync`.
- Partial Payments & Refunds (v0.3.0) интегрированы в API и Messaging.
- Observability: готовые /metrics, health/readiness.
- Outbox: транзакционная доставка событий.
