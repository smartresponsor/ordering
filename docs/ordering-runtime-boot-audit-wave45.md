# Ordering runtime boot audit — wave 45

Wave 45 repaired a focused runtime-import cluster around refund status, shipment view repository,
API resource wrappers, payment command wrappers, and shipment carrier polling support.

Verified by `php -l` on all touched PHP files.

Targeted stale-import counts after the wave:
- `App\ValueObject\Order\RefundStatus`: 0
- `App\Repository\Order\OrderShipmentViewRepository`: 0
- `App\ApiResource\Order\OrderResource`: 0
- `App\Api\Order\Resource\OrderResource`: 0
- `App\Message\Command\Order\OrderPartialPaymentCommand`: 0
- `App\Message\Command\Order\OrderRefundCommand`: 0
- `App\Integration\Shipment\CarrierInterface`: 0
