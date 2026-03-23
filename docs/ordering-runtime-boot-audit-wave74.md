# Ordering runtime boot audit — wave 74

Wave 74 focuses on shipment, reservation, saga, repricing, and payment/refund command-handler coherence.

Touched clusters:
- ShipmentProcessorServiceInterface / ShipmentProcessorService
- ReservationServiceInterface / ReservationService
- RetryOrderSagaHandlerInterface / RetryOrderSagaHandler
- StartOrderSagaHandlerInterface / StartOrderSagaHandler
- RecalculateOrderPricingHandlerInterface / RecalculateOrderPricingHandler
- OrderPaymentCommandHandlerInterface / OrderPaymentCommandHandler
- OrderRefundCommandHandlerInterface / OrderRefundCommandHandler
- OrderPartialPaymentCommandHandlerInterface / OrderPartialPaymentCommandHandler

Validation:
- `php -l` passed on all touched PHP files in this wave.
