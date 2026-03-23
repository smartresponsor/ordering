# Ordering runtime boot audit — wave 39

Wave 39 fixed a concrete controller/service coherence layer in the current slice.

## Closed stale runtime imports
- `App\Service\Order\Payment\OrderPaymentService` → `App\Service\Order\OrderPaymentService`
- `App\Service\Order\Webhook\WebhookIdempotencyService` → `App\Service\Order\WebhookIdempotencyService`
- `App\Service\Order\Outbox\IdempotencyService` → `App\Service\Order\IdempotencyService`

## Closed missing runtime symbol
- Added `App\Api\Order\Dto\OrderCreateInput`

## Service coherence
`OrderPaymentService` now provides methods actually invoked by controllers/webhook flow:
- `applyPartialPayment(...)`
- `refund(...)`

## Validation
- `php -l` passed on all touched files.
