# Ordering runtime boot audit вЂ” wave 55

## Fixed
- `App\Ordering\Service\Order\OrderWorkflowService`
  - `cancel()` and `refund()` no longer call a missing method.
  - Workflow event publishing now routes through a local helper backed by `App\Ordering\Service\Outbox\OutboxPublisher`.
  - Both flows flush after applying transition and publishing outbox event.

## Verification
- `php -l src/Service/Order/OrderWorkflowService.php` passes.
