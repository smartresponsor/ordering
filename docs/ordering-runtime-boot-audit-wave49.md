# Ordering runtime boot audit — wave 49

Wave 49 focused on outbox constructor/runtime compatibility.

## Findings closed
- DI config previously created `App\Service\Outbox\OutboxPublisher` with only the entity manager argument.
- tests and runtime code in the same slice instantiate `OutboxPublisher` in multiple shapes:
  - bus only
  - entity manager + bus
  - repository + entity manager + bus
- legacy `App\Service\Order\OutboxPublisher` still used stale outbox entity/repository method names.

## Result
- current slice now supports the active constructor shapes without falling apart at boot/test time.
- outbox publisher wiring is closer to a stable runtime baseline for subsequent container/doctrine sweeps.
