# Ordering Runtime Boot Audit — Wave 76

Wave 76 closes a hidden gateway/carrier contract cluster.

## Facts
- `App\Service\Order\PaymentGatewayInterface` now matches the call shape already used by PayPal/Stripe gateway implementations.
- `App\ServiceInterface\Order\CarrierInterfaceInterface` now declares the shipping contract actually implemented by carrier services.
- `OrderShipmentGatewayInterfaceInterface` now imports the real `App\Entity\Order\Order` type.
- Concrete services are now formally tied to canonical gateway/carrier interfaces.
