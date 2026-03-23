# Payment Stub for Refunds (StripeStubGateway)

Дата: 2025-10-09

## Что это
Лёгкая интеграция для Refund-потока:
- `PaymentGatewayInterface::refund(paymentId, amountMinor, currency)`
- Имплементация: `StripeStubGateway` — всегда успешный возврат при amountMinor > 0.

## Подключение
```yaml
# config/services/order_payment_stub.yaml
services:
  OrderComponent\Order\Integration\Payment\StripeStubGateway:
    arguments:
      $secret: '%env(default::STRIPE_SECRET)%'
  OrderComponent\Order\Service\Order\RefundProcessor:
    arguments:
      $gateway: '@OrderComponent\Order\Integration\Payment\StripeStubGateway'
```

## Тест
```
vendor/bin/phpunit tests/Service/RefundProcessorWithGatewayTest.php
```
