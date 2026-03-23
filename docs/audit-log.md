# Audit Log

- Format: **NDJSON**, one record per line.
- Fields: `ts`, `kind`, `actor`, `method`, `path`, `status`, `ms`, `request_id`, `trace_id`.
- Location: `var/audit/audit.ndjson`.

Glue:
```php
$log = new \SmartResponsor\Order\Security\Audit\AuditLogger(__DIR__.'/../var/audit/audit.ndjson');
$app->use((new \SmartResponsor\Order\Security\Http\AuditMiddleware($log))(...));
```
