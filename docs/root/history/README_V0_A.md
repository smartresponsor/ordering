# OrderComponent v0.A — Payment Integration & Projection

Дата: 2025-10-09

## Что включено
- Подписчик на `payment.captured` и `payment.refunded` через Messenger.
- Сервис `OrderPaymentReconciliationService` — обновляет статусы заказов.
- Проекция `OrderPaymentView` с таблицей `order_payment_projection`.
- Команда `order:sync:payments` для ручной синхронизации.
- Миграция `Version2025100901_order_payment_projection`.

## Дальше
- Добавить связь с Taxation и Shipment для расчёта итогов.
- E2E-тест между Order ↔ Payment компонентами.
