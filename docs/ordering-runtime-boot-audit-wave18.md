# Ordering runtime boot audit — wave 18

Wave 18 repairs the `problem / idempotency / outbox` cluster by adding missing runtime classes referenced from configuration and by normalizing malformed outbox service registration.

Heuristic config-level `App\...` missing-class references:

- before wave 18: 56
- after wave 18: 45
- resolved in this wave: 11

Resolved references include:

- `App\Infrastructure\Order\Http\ProblemExceptionListener`
- `App\Infrastructure\Order\Idempotency\IdempotencyRequestListener`
- `App\Infrastructure\Order\Outbox\OutboxProcessor`
- `App\Infrastructure\Order\Outbox\Command\OutboxRunCommand`
- `App\Service\Order\Http\ViolationNormalizer`
- `App\Service\Order\Http\ProblemFactory`
- `App\Service\Order\Outbox\ExponentialBackoffStrategy`
- `App\Service\Order\Outbox\DlqPublisher`
- `App\Service\Order\Outbox\DlqService`
- malformed config reference to `App\Command\Order\OutboxProcessCommand` removed by normalizing `config/services/order_outbox.yaml`

All touched PHP files passed `php -l`.
