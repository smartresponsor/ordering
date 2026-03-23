# Payments Metrics (Prometheus names)

- `payment_provider_latency_ms` (histogram) — per provider/op.
- `payment_provider_error_total` (counter) — per provider/op/code.
- `payment_webhook_verify_fail_total` (counter).
- `payment_authorize_total`, `payment_capture_total`, `payment_refund_total` (counter).
- `payment_state` (gauge: -1=failed, 0=pending, 1=succeeded).

Labels: `provider`, `op`, `tenant`, `code`.
