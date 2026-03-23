# Ordering runtime boot audit — wave 38

Fixed current-slice runtime coherence issues:
- WebhookIdempotencyService now receives WebhookLogRepository, matching its constructor.
- WebhookLogRepositoryInterface now imports App\\Entity\\Order\\WebhookLog, so the method signature resolves to a real class.
- ShipmentService now exposes markShipped(), matching current listeners/handlers that already call it.
