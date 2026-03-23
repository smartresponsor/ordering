# Ordering runtime boot audit - wave 17

Scope: audit and observability cluster.

Result:
- added local runtime classes required by config/services_audit.yaml, config/packages/order_http_audit.yaml, config/packages/order_observability.yaml, config/services.monitoring.yaml, and config/packages/order.audit.yaml
- syntax check passed for all touched PHP files
- missing `App\\...` class references found in `config/*` reduced from 68 to 57

Remaining major clusters after this wave:
- problem/idempotency/outbox
- payment/shipment/taxation adapters
- pricing strategies
- doctrine/read-model/openapi
