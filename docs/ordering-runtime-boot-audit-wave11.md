# Wave 11 — entity syntax repair

Base slice: `ordering-10-boot-sweep-wave10-cumulative-snapshot.zip`

This wave repairs concrete syntax blockers in the `src/Entity/Order` layer and adjacent nested entity trees.

## Fixed files
- src/Entity/Order/Delivery.php
- src/Entity/Order/Payment.php
- src/Entity/Order/OutboxRecord.php
- src/Entity/Order/OrderSmokeTest.php
- src/Entity/Order/Traits/OrderPriceSnapshotTrait.php
- src/Entity/Order/Entity/Delivery/Delivery.php
- src/Entity/Order/Entity/Order/Order.php
- src/Entity/Order/Entity/Order/OrderEntity.php
- src/Entity/Order/Entity/Order/OrderItemEntity.php
- src/Entity/Order/Entity/Order/OrderPaymentEntity.php
- src/Entity/Order/Entity/Order/OrderRefundEntity.php
- src/Entity/Order/Entity/Order/OrderShipmentEntity.php
- src/Entity/Order/Entity/Order/OrderTaxationEntity.php
- src/Entity/Order/Entity/Payment/Payment.php
- src/Entity/Order/Entity/OrderSmokeTest.php

## Notes
- Removed duplicate embedded `<?php` fragments.
- Replaced broken `SmartResponsor\...` value-object references in repaired files with scalar state where needed to regain syntax validity.
- Rebuilt malformed entity-interface implementations into valid PHP classes.
- Rebuilt malformed trait body in `OrderPriceSnapshotTrait`.
- Verified syntax sweep for `src/Entity/Order/**/*.php`: 0 parse errors.
