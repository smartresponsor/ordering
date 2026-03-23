# Ordering runtime boot audit - wave 48

Wave 48 repaired the Outbox runtime cluster.

## Findings closed
- `OutboxWriter` previously called `new OutboxMessage($topic, $payload)` with the wrong arity and wrong semantic shape for the entity constructor.
- `OutboxProcessor` and `OutboxMessengerDispatcher` previously called nonexistent `getEventName()` on `OutboxMessage`.
- Both processing flows were traversing all messages instead of the undispatched subset and removed messages instead of using the entity's dispatched lifecycle.

## Result
The touched Outbox service files are now syntactically valid and internally coherent with:
- `App\Entity\Outbox\OutboxMessage`
- `App\Repository\Outbox\OutboxMessageRepository`
- `App\Message\OrderEventMessage`
