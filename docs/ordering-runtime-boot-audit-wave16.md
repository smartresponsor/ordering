# Ordering runtime / boot audit — wave 16

Wave 16 targeted the `security + webhook` cluster left open after wave 15.

## Repaired
- `App\\Security\\WebhookSignatureVerifier`
- `App\\Http\\Subscriber\\WebhookHmacSubscriber`
- `App\\Security\\JwtScopeVoter`
- `App\\Security\\Jwt\\JwtTenantResolver`
- `App\\RateLimiter\\TenantKeyResolver`
- `App\\EventListener\\OrderApiRateLimitListener`
- `App\\EventListener\\OrderTenantRateLimitListener`
- `App\\Service\\Order\\Billing\\IdempotencyGuard`
- `App\\Service\\Order\\Billing\\WebhookHandler`
- `App\\Service\\Order\\Billing\\RefundService`

## Config touched
- `config/services.security.yaml`
- `config/services/order_security.yaml`
- `config/services/order_tenant.yaml`

## Verification
- `php -l` passes on all touched PHP files
- config-level missing `App\\...` class references in `config/*` reduced from 100 to 90 in local sweep

## Remaining large clusters
- audit / observability
- outbox
- monitoring / crypto
- doctrine / read-model legacy mappings
- pricing / shipment / payment gateway strategy clusters
