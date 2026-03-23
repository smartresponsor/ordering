# Ordering runtime boot audit — wave 61

Wave 61 closes a hidden listener/guard contract mismatch cluster.

## Findings addressed
- `OrderTenantRateLimitListenerInterface` previously referenced `RequestEvent` without import
- `OrderWorkflowGuardSubscriberInterface` previously referenced `GuardEvent` without import
- service-layer wrappers were present but were not formally tied to the canonical interface layer

## Result
- interface type resolution now targets real Symfony event classes
- `OrderTenantRateLimitListener` now implements `OrderTenantRateLimitListenerInterface`
- `OrderWorkflowGuardSubscriber` now implements `OrderWorkflowGuardSubscriberInterface`
