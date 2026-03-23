# v0.23 — Pricing & Taxation Consistency

## Что добавлено
- VO: Money, Currency, TaxRate, Discount
- Entity snapshot: OrderPriceDetail
- Service: OrderPricingService
- Strategies: PromotionStrategyInterface, TaxationStrategyInterface (+ default impl)
- Command/Handler: RecalculateOrderPricingCommand/Handler
- ReadModel: OrderPriceView
- Doctrine Migration: creates `order_price_detail`
- Tests: unit & integration

## Wiring
`config/services/order_pricing.yaml` — регистрирует стратегии, сервис, и message handler.

## Дальше
- Подключить OrderRepository в Handler и доставать позиции заказа
- Сохранять snapshot к агрегату Order
- Согласовать с Partial Payment / Refund Flow