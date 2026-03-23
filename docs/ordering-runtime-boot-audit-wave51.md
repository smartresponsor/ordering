# Ordering runtime boot audit — wave 51

Wave 51 closes a pricing wrapper compatibility cluster in the current slice.

Files added:
- `src/Service/Order/Pricing/PriceCalculator.php`
- `src/Service/Order/Pricing/DefaultPromotionStrategy.php`
- `src/Service/Order/OrderPricing/Strategy/FlatPromotionStrategy.php`
- `src/Service/Order/OrderPricing/Strategy/FlatTaxationStrategy.php`

All touched PHP files pass `php -l`.
