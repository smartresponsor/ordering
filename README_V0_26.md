# v0.26 — Command Layer & API (Partial Payment / Refund)

## Содержимое
- Команды + хендлеры (Messenger): `OrderPartialPaymentCommand`, `OrderRefundCommand`.
- Идемпотентность: `IdempotencyKey` (ключ хранится, повтор игнорируется).
- Outbox: `OutboxMessage` (type + payload) — для публикации в брокер (отдельный процессор).
- API Platform: DTO inputs + Processors + POST ресурсы.
- Миграции: idempotency_key, outbox_message.
- Тесты: функциональные POST на эндпоинты.

## Подключение
1) Включи файл `config/services/order_commands.yaml`.
2) Убедись, что Messenger настроен (sync/in_memory/rabbitmq). 
3) Прогони миграции Doctrine.
4) (Опционально) Реализуй OutboxProcessor (читает `outbox_message`, публикует события).

## Дальше
- Валидация баланса оплат/возвратов относительно суммы заказа.
- Security (JWT scopes/role-based), rate limiting.
- Трассировка (OpenTelemetry) и idempotency TTL clear task.