# Ordering runtime boot audit вЂ” wave 39

Wave 39 fixed a concrete controller/service coherence layer in the current slice.

## Closed stale runtime imports
- `App\Ordering\Service\Order\Payment\OrderPaymentService` в†’ `App\Ordering\Service\Order\OrderPaymentService`
- `App\Ordering\Service\Order\Webhook\WebhookIdempotencyService` в†’ `App\Ordering\Service\Order\WebhookIdempotencyService`
- `App\Ordering\Service\Order\Outbox\IdempotencyService` в†’ `App\Ordering\Service\Order\IdempotencyService`

## Closed missing runtime symbol
- Added `App\Api\Order\Dto\OrderCreateInput`

## Service coherence
`OrderPaymentService` now provides methods actually invoked by controllers/webhook flow:
- `applyPartialPayment(...)`
- `refund(...)`

## Validation
- `php -l` passed on all touched files.
