# OrderComponent v0.F — Advanced Taxation & Multi-Currency

Дата: 2025-10-09

## Что включено
- **Налоги**: `TaxationStrategyInterface`, `FlatTaxStrategy`, `ProgressiveTaxStrategy`, `TaxRuleSet`.
- **Валюты**: `ExchangeRate`, `ExchangeRateProviderInterface`, `InMemoryRateProvider`, `CurrencyConversionService`.
- **Расчёты**: `AdvancedPriceCalculator` (налоги после/до скидок, мультивалюта).
- **API**: POST `/orders/pricing/convert`.
- **Тесты**: `AdvancedTaxationTest`, `MultiCurrencyTest`.

## Пример запроса
```json
{
  "items": [
    {"priceMinor": 1000, "quantity": 2, "currency": "USD"},
    {"priceMinor": 800, "quantity": 1, "currency": "EUR"}
  ],
  "displayCurrency": "USD",
  "discountPercent": 0.1,
  "taxMode": "progressive",
  "brackets": [
    {"from": 0, "to": 10000, "rate": 0.0},
    {"from": 10001, "to": 50000, "rate": 0.1},
    {"from": 50001, "to": null, "rate": 0.2}
  ],
  "taxAfterDiscount": true
}
```

## Подключение
- Импортируй `config/services/order_taxation.yaml` и `config/api_platform/resources.pricing_convert.yaml`.
- Для продакшн-курсов замени `InMemoryRateProvider` на провайдер c кешем и внешним API.
