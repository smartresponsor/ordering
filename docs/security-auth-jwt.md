# JWT Auth via JWKS

## Options
- **Ingress (Envoy)**: use `config/envoy/jwt-authn.yaml` (recommended). Envoy verifies JWT and forwards payload header.
- **In-app fallback**: use `JwtVerifier` + `JwksCache` and `AuthMiddleware`.

## Glue (in-app)
```php
$cfg = json_decode(file_get_contents(__DIR__.'/../config/security/jwt.json'), true);
$cache = new \SmartResponsor\Order\Security\JWT\JwksCache($cfg['jwks_url'], __DIR__.'/../var/security/jwks.json', $cfg['cache_ttl_sec']);
$ver = new \SmartResponsor\Order\Security\JWT\JwtVerifier($cfg['issuer'], $cfg['audience'], $cache, $cfg['leeway_sec']);
$app->use((new \SmartResponsor\Order\Security\Http\AuthMiddleware($ver))(...));
```
