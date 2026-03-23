# Ordering runtime boot audit — wave 71

Wave 71 repairs hidden idempotency guard/policy service-interface coherence.

## Verified files
- `src/ServiceInterface/Order/IdempotencyGuardInterface.php`
- `src/ServiceInterface/Order/IdempotencyKeyPolicyInterface.php`
- `src/ServiceInterface/Order/HttpIdempotencyGuardInterface.php`
- `src/ServiceInterface/Order/WorkerIdempotencyGuardInterface.php`
- `src/Service/Order/IdempotencyGuard.php`
- `src/Service/Order/IdempotencyKeyPolicy.php`
- `src/Service/Order/HttpIdempotencyGuard.php`
- `src/Service/Order/WorkerIdempotencyGuard.php`

## Result
`php -l` passes on all touched files in this wave.
