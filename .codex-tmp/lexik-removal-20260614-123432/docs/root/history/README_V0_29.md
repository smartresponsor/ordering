# v0.29 — JWT Integration (scaffold) + Tenant Rate Limiter + OrderMetrics Projection

## Что включено
- LexikJWTAuth **scaffold** (`config/packages/lexik_jwt_authentication.yaml`) — подключи ключи и `JWT_PASSPHRASE`.
- Tenant-aware limiter: `TenantKeyResolver`, `OrderTenantRateLimitListener`, `rate_limiter.tenant.yaml`.
- Metrics projection: `OrderMetrics` entity, `OrderMetricsProjector`, консольная `order:readmodel:sync-metrics`, миграция.
- API Resource `OrderMetricsResource` (GET collection, фильтры vendor/day).

## Подключение
1) Сгенерируй ключи для LexikJWT и укажи `JWT_PASSPHRASE`.  
2) Импортируй `config/services/order_tenant.yaml`, `config/packages/rate_limiter.tenant.yaml`.  
3) Импортируй `config/services/order_metrics.yaml` и выполни миграцию `order_metrics`.  
4) Построй read-model:  
   ```bash
   php bin/console order:readmodel:sync-metrics 2025-10-08
   ```

## Дальше
- Реальный state provider для `OrderMetricsResource` (DB → DTO).  
- Кеширование метрик (Symfony Cache, 60s).  
- Расширить projector на интервалы (неделя/месяц).  