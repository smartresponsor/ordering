# Ordering runtime boot audit вЂ” wave 52

Wave 52 repaired the remaining contract drift around the Order outbox entity.

## Repaired layer
- `App\Ordering\Entity\Order\OutboxMessage`
- `App\RepositoryInterface\Order\OutboxRepositoryInterface`

## Concrete effect
The current slice had simultaneous expectations for `OutboxMessage` as:
- a plain Doctrine entity with setters/getters
- a richer outbox runtime entity used by relay/publisher/tests

After wave 52, the current slice supports both usage patterns in a single entity contract.
