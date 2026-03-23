# AA Plan — Router prod-level

- Health scoring per region: экспоненциальный decay/boost на успехах/ошибках.
- Canary routing: провайдер-кандидат на доле трафика per region, с авто-промо по успехам.
- Weighted routing: вес = base_weight × health_score.
- Quotas: token-bucket per provider×tenant×region.
- Cost guardrails: ограничение дорогих маршрутов.
- Observability: метрики, decision-log, дашборды, алерты.
- Drills: синтетика выбора, провайдер-хаос (rate-limit, 5xx, timeouts).
