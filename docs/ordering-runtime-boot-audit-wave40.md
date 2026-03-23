# Ordering runtime boot audit — wave 40

## Focus

Runtime/interface coherence in webhook/idempotency/shipment-support layers.

## Findings addressed

- `RefundWebhookController` called `acceptOnce(...)`, but `WebhookIdempotencyService` exposed `handleOnce(...)`.
- Several service/controller-interface files referenced symbols without imports or referenced stale interface trees.

## Result

- `RefundWebhookController` now dispatches through `WebhookIdempotencyService::handleOnce(...)`.
- `OrderIdempotencyService`, `IdempotencyService`, and `OrderShipmentService` are aligned to current `App\ServiceInterface\Order\...` contracts.
- Key interface files are now explicit about imports and type resolution.
