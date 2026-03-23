# Async Scale & Tenant Isolation — Guide

## Partitioning
- Path layout: `var/queue/<tenant>/<topic>/{pending,processing}`
- Router: shard = crc32(tenant:topic) % N (see `ShardRouter`).

## Worker pool
- Visibility timeout requeues expired leases back to `pending`.
- Deterministic failures (`payload.fail=true`) for smoke of retry.

## Tenant isolation
- Per-tenant partitions, per-tenant quotas (max workers, RPS).
- Rate-limit hooks to be enforced at API gateway; worker reads policy for autoscale bounds.

## Swap-in
- Replace `FileQueue` with SQS/Kafka/etc by implementing `QueueInterface`.
- Keep worker & router unchanged.
