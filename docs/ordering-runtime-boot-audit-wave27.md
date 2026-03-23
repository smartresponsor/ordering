# Ordering runtime boot audit — wave 27

Wave 27 targeted the outbox / messenger / audit helper layer.

## Verified syntax
`php -l` passed on all touched PHP files in this wave.

## Concrete repairs
- `OutboxPublisher` now matches the current service definition and exposes `replay()` used by `OutboxReplayCommand`
- `OutboxMessage` no longer accepts the wrong type for `eventType`
- `OutboxMessageRepository` now queries the actual `dispatched` field
- audit helper constructors now match the config argument names used in package service files
- messenger idempotency middleware no longer hard-fails on the current config argument shape
