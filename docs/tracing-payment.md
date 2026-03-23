# Tracing (OTLP)

- Add spans around provider calls: `payment.authorize`, `payment.capture`, `payment.refund`.
- Attributes: `provider`, `amount`, `currency`, `order_id`, `payment_id`.
- Export via OTLP HTTP/gRPC to collector (Jaeger/Tempo): see `config/otel/exporter.yaml`.
