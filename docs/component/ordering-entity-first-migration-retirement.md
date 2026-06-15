# Ordering entity-first migration retirement

## Intent

Ordering is moved toward entity-first schema ownership. SQL/PHP migrations are no longer treated as the canonical model source at this stage.

## Retired schema-first sources

- `Ordering/migrations/**`
- `Ordering/sql/**`
- `Ordering/src/Migrations/**`
- `Ordering/tools/sql/**`

## Old monolith reconciliation

Recovered missing concepts from the old monolith `Entity/Order` slice:

- `OrderStatusEntity` from `OrderStatus`
- `OrderStorageEntity` from `OrderStorage`
- `OrderLogEntity` from `OrderLog`
- `OrderPaymentTranslationEntity` from `OrderPaymentEnUs`
- `OrderShipmentTranslationEntity` from `OrderShipmentEnUs`

Locale-specific `*EnUs` classes were not preserved as concrete class names. They were normalized to translation entities.

## Migration-only concepts promoted to entities

- `OrderReturnPolicyEntity`
- `OrderTaxationAuditEntity`
- `OrderPriceAuditEntity`
- `OrderArchiveEntity`
- `OrderMetricsExportLogEntity`
- `OrderProviderRouteEntity`
- `OrderDeadLetterProjectionEntity`
- `OrderSecurityKeyEntity`

## Objecting boundary

New entities use Objecting embeddable traits for identity/audit/soft-delete/state/locale where applicable. Generic system fields are not reintroduced as local Ordering traits.

## Remaining follow-up

Run Doctrine metadata validation in the runtime host after dependencies are installed. Existing legacy entities still contain direct local `id/slug/createdAt/updatedAt` fields and should be migrated to Objecting compatibility bridges in a later pass to avoid breaking the current runtime surface in one change.
