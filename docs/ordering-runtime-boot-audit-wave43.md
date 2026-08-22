# Ordering runtime boot audit wave 43

This wave closes a runtime gap around missing domain events and subscriber classes.

Closed in this wave:
- dispute event classes
- refund initiated / fully refunded event classes
- shipment delivered / return-expired event classes
- inventory stock event classes
- App\Ordering\Subscriber\Order subscriber classes expected by integration tests
- InventoryService import retargeted to the local App\Ordering\Service\Order\InventoryGatewayInterface
