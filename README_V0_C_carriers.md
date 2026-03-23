# OrderComponent v0.C — Shipment Carriers (UPS/DHL/FedEx stubs)

Дата: 2025-10-09

## Что добавлено
- Интерфейс `CarrierInterface` и DTO `ShipmentUpdate`.
- Адаптеры-заглушки: `UPSCarrier`, `DHLCarrier`, `FedExCarrier`.
- Сервис `CarrierPollingService` с `#[TaggedIterator('order.shipment.carrier')]` — собирает все адаптеры автоматически.
- Команда `order:carrier:poll UPS ORD-1 JD000223456789` — обновляет проекцию доставки.

## Конфигурация
Импортируй DI:
```yaml
# config/services/order_carriers.yaml
services:
  OrderComponent\Order\Integration\Shipment\UPSCarrier:
    tags: ['order.shipment.carrier']
    arguments: { $apiKey: '%env(default::UPS_API_KEY)%' }
  OrderComponent\Order\Integration\Shipment\DHLCarrier:
    tags: ['order.shipment.carrier']
    arguments: { $apiKey: '%env(default::DHL_API_KEY)%' }
  OrderComponent\Order\Integration\Shipment\FedExCarrier:
    tags: ['order.shipment.carrier']
    arguments: { $apiKey: '%env(default::FEDEX_API_KEY)%' }
  OrderComponent\Order\Service\Order\CarrierPollingService:
    autowire: true
    autoconfigure: true
```

## Использование
```bash
php bin/console order:carrier:poll DHL ORD-1 JD000223456789
php bin/console order:carrier:poll UPS  ORD-2 1Z999AA10123456780
php bin/console order:carrier:poll FedEx ORD-3 FEDEX123R
```

## Дальше
- Реальные SDK-подключения и подписи запросов.
- Планировщик (Scheduler/Cron) для массового опроса активных треков.
- Лимиты и ретраи, DLQ при ошибках интеграций.
