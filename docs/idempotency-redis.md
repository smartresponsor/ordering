# Redis Idempotency

- Класс `RedisKeyStore`: `checkAndStore(key, scope, ttlSec)` — atomic SET NX EX.
- Используйте для transitions и платёжных операций с TTL 1 ч.
- Fallback: file-based KeyStore остаётся как опция для dev.
