# Runbook · AB (Idempotency + DLQ)

## Idempotency
- Verify duplicate POSTs are blocked (HTTP guard demo).
- Ensure worker guard blocks same payload hash.
- TTL defaults to 600 seconds; adjust per endpoint criticality.

## Retry Storm
- Threshold: 5 events/minute per key (default).
- On block: increment `retry_storm_blocked` metric and alert.

## DLQ Console
- `bin/dlq-console.php seed` — seed demo items.
- `list`, `requeue`, `discard` — manage items; all actions audited to `var/audit.log`.

## Metrics
- Export counters via MetricExporter; scrape with your Prometheus.
- Key metrics: `idempotency_hit`, `dlq_total`, `dlq_requeued`, `retry_storm_blocked`.
