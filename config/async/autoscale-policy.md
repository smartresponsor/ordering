# Autoscale Policy (reference)

Goal: keep backlog age < 30s and CPU < 70%.

Inputs:
- `queue_depth` per shard/tenant
- `backlog_age_seconds` (now - oldest_visible_at)
- `process_rate_eps` (events/sec)
- `cpu_utilization` of worker group

Policy (example):
- Scale out by +1 worker when:
  - backlog_age_seconds > 15s for 3 consecutive checks OR
  - queue_depth > (process_rate_eps * 30s)
- Scale in by -1 worker when:
  - backlog_age_seconds < 5s for 10 consecutive checks AND
  - cpu_utilization < 30%

Safety:
- Min workers per tenant: 1
- Max workers per tenant: from quotas (see tenant-policy.json)
