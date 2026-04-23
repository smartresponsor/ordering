# OrderComponent v0.D — FULL (Returns & Refunds + API Platform + Payment Stub)

Дата: 2025-10-09

## Что включено
- Returns & Refunds: сущности, сервисы, события, подписчики.
- API Platform: POST `/orders/{id}/returns`, GET `/orders/{id}/refunds`.
- Payment Stub: `PaymentGatewayInterface`, `StripeStubGateway`, DI-конфиг пример.
- CI: GitHub Actions (PHP 8.2/8.3), PHPStan (max), PHPUnit 10.

## Подключение
1. Скопируй `src/` и `config/api_platform/*.yaml`, `config/services/*.yaml` в проект.
2. Установи зависимости: `composer install`.
3. Прогони миграции: `php bin/console doctrine:migrations:migrate`.
4. Проверь API: см. ресурсы выше.

## ENV
- `STRIPE_SECRET` — опционально для `StripeStubGateway`.

## Тесты
```bash
vendor/bin/phpunit
vendor/bin/phpstan analyse
```
