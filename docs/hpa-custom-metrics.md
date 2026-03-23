# HPA with Custom Metrics

- Prometheus Adapter мапит `queue_backlog_age_seconds` в Custom Metrics API.
- HPA `order-worker-queue` таргетится на среднее значение 20s по подам.
- Требуется установленный `prometheus-adapter` и CM `prom-adapter-config`.
