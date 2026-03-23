# Ordering runtime boot audit — wave 28

## Findings addressed
- `config/packages/messenger.php` referenced `App\Middleware\IdempotencyMiddleware` while the canonical implementation lives under `App\Messenger\Middleware\IdempotencyMiddleware`.
- `config/routes/monitoring.yaml` duplicated the `_health/order` concern already covered by `config/routes/health.yaml`.
- `config/services/order_security.yaml` needed newline normalization after prior edits.

## Repairs
- Normalized messenger middleware reference to the canonical namespace.
- Added a compatibility wrapper under `App\Middleware\IdempotencyMiddleware`.
- Removed duplicate health route from `monitoring.yaml`, keeping metrics and readiness routes.
- Rewrote `order_security.yaml` with clean formatting.
