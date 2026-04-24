# Order · AC Bucket (Security Polish · v1)

Scope: JWKS on ingress, webhook signing (HMAC + RSA), secret rotation policy, CSP and rate-limit guard,
SQL DDL for key storage and audit projection, CLI demos, tests, and CI recipe.

- Language: PHP 8.2
- Comments: English only
- Naming: singular classes/methods, single hyphen archives
- Data: Postgres for Data (key store); MySQL for Infrastructure (audit projection)
- Owner: Marketing America Corp
- Author: Oleksandr Tishchenko <dev@smartresponsor.com>

## Quick Start (CLI demos)
```bash
composer install

# 1) Generate RSA key and JWKS file (demo repository under var/)
php bin/jwk-generate.php

# 2) Print JWKS (serve from repo; can be used behind web server)
php bin/jwks-serve.php > jwks.json

# 3) Sign and verify payload with HMAC
echo -n '{"ok":true}' | php bin/webhook-sign.php hmac secret-123 > sig.txt
cat sig.txt | php bin/webhook-verify.php hmac secret-123

# 4) Sign and verify payload with RSA (kid from jwk-generate step)
echo -n '{"ok":true}' | php bin/webhook-sign.php rsa var/key/private.pem kid-demo > sig_rsa.txt
cat sig_rsa.txt | php bin/webhook-verify.php rsa var/key/public.pem
```

## Structure
- `src/Layer/Order/JwkKey.php` (value)
- `src/Layer/Order/JwkRepositoryInterface.php`, `src/Layer/Order/FileJwkRepository.php`
- `src/Layer/Order/JwksIssuer.php` (JWKS JSON builder)
- `src/Layer/Order/WebhookSignerHmac.php`, `src/Layer/Order/WebhookVerifierHmac.php`
- `src/Layer/Order/WebhookSignerRsa.php`, `src/Layer/Order/WebhookVerifierRsa.php`
- `src/Layer/Order/SecretRotationPolicy.php`, `src/Layer/Order/KeyRotationManager.php`
- `src/Layer/Order/CspMiddleware.php`, `src/Layer/Order/RateLimit.php`
- `src/LayerInterface/Order/*` — mirror interfaces
- `bin/*` — runnable demos
- `sql/postgres/security_key.sql` — key store (Data)
- `sql/mysql/security_audit_projection.sql` — audit projection (Infra)
- `test/*` — basic tests

No TODOs; all demos run offline. Comments in English only.


## API and archived delivery metadata

- Primary API specs live under `api/openapi/`.
- Archived root-era API snapshots live under `api/openapi/archive/`.
- Historical delivery metadata and milestone files live under `docs/root/archive/manifests/`.

