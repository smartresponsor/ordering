# OrderComponent v0.D — Returns & Post-Delivery Refunds (full)

Дата: 2025-10-09

## Что включено
- API Platform ресурсы:
  - **POST** `/orders/{id}/returns` — создание возврата (контроллер `ApiReturnController`).
  - **GET** `/orders/{id}/refunds` — список рефандов (контроллер `ApiRefundController`).
- DTO: `ReturnRequestInput`, `ReturnRequestOutput`, `RefundView`.
- Конфигурация API Platform в `config/api_platform/resources.order.yaml`.
- CI: GitHub Actions (`.github/workflows/order_component_ci.yml`), PHPStan, PHPUnit.

## Запуск
```bash
composer install
vendor/bin/phpunit
```

## Подключение в Symfony
- Убедись, что API Platform установлен и загружает конфиги из `config/api_platform/*.yaml`.
- Сервисы возвращают DTO, не энтити.
