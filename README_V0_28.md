# v0.28 — Security (JWT Scopes) & Rate Limiting

## Что включено
- `JwtScopeVoter` с ролями: `ROLE_ORDER_VIEW`, `ROLE_ORDER_PAYMENT`, `ROLE_ORDER_REFUND`.
- RateLimiter `order_api` (100 req/min).
- Листенер `OrderApiRateLimitListener` — применяет лимит к `/api/orders...`.
- Monolog channel `order_security` + логгер `OrderApiAuditSubscriber` (kernel.terminate).
- Примеры security для API Platform ресурсов.

## Подключение
1) Импортируй `config/services/order_security.yaml`, `config/packages/rate_limiter.yaml`, `config/packages/monolog.order_audit.yaml`.
2) Включи `JwtScopeVoter` и добавь `security:` атрибуты в ресурсы API Platform. Пример:
   ```php
   #[ApiResource(security: "is_granted('ROLE_ORDER_VIEW')")]
   class OrderPriceViewResource { ... }
   ```
3) Для POST-операций оплаты/возврата:
   ```php
   new Post(security: "is_granted('ROLE_ORDER_PAYMENT')")
   new Post(security: "is_granted('ROLE_ORDER_REFUND')")
   ```
4) Перезапусти приложение. Логи смотри в `var/log/order_security.log`.

## Тесты
- `JwtScopeVoterTest`, `RateLimiterIntegrationTest` (минимальные).

## Дальше
- Подключить JWT провайдер (lexik/jwt-authentication-bundle или др.).
- Добавить глобальный API key throttling per-tenant.