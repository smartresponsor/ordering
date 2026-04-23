# v0.24 — API Platform exposure + Snapshot & Audit

## Что входит
- Trait `OrderPriceSnapshotTrait` — добавь в свою сущность `Order` (OneToOne -> `OrderPriceDetail`).
- Entity `OrderPriceAudit` — история пересчётов.
- Controller `/api/orders/{id}/price` — DTO `OrderPriceView` (заглушка провайдера).
- Subscriber `OrderWorkflowSubscriber` — на `order.placed` выполняет пересчёт и пишет snapshot + audit.
- Repositories + services config + api_platform.yaml.
- Миграция: `order_price_audit`.

## Внедрение
1. Подключи trait в свой `Order`:
   ```php
   use OrderComponent\Entity\Order\Traits\OrderPriceSnapshotTrait;

   class Order { use OrderPriceSnapshotTrait; }
   ```
2. Переключи `OrderPriceController` на реальный ReadModel provider/репозиторий.
3. Уточни событие `order.placed` под твой контракт.
4. Прогони миграции, `bin/console doctrine:migrations:migrate`.