# v0.25 — State Provider + Filters + Partial Payment & Refund

## Содержимое
- State Provider `OrderPriceProvider` + `OrderPriceViewResource` (API Platform).
- Partial Payment / Refund сущности и миграции.
- RefundPolicyService с окном возврата.
- Audit API ресурс с фильтрами (Search/Date).
- Тесты: RefundPolicyService, список аудита.

## Дальше
- Репозитории и провайдеры подключить к реальной БД/проекциям.
- Политики возвратов расширить (post-delivery, RMA, restocking fee).
- API: POST endpoints для partial payment и refund команд (Messenger).