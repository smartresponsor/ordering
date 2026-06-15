# Ordering

Symfony-oriented ordering component with Doctrine-first storage, API surface, background workers, deploy assets, and archived root documentation.

- Language: PHP 8.2+
- Runtime style: Symfony app namespace (`App\\...`)
- Persistence: Doctrine ORM entities + migrations as primary schema truth
- Owner: Marketing America Corp
- Author: Oleksandr Tishchenko <dev@smartresponsor.com>

## Current repository surfaces
- `src/` — application code, entities, services, API resources
- `migrations/` — schema evolution
- `api/openapi/` — current API specifications
- `deploy/` — Docker, Compose, Helmfile, Kubernetes, security, observability
- `docs/` — operational and product documentation
- `docs/root/` — materials migrated out of the root to keep the RC surface clean
- `tests/` — automated tests
- `tools/` — support tooling and smoke/manual helpers

## Quick start
```bash
composer install
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:schema:validate
php vendor/bin/phpunit --stop-on-error --no-progress
```

## Container and deploy entry points
- Local and production compose files live under `deploy/`
- Helmfile and values live under `deploy/order/`
- Security manifests live under `deploy/security/`
- Observability assets live under `deploy/observability/`

## API and archived delivery metadata
- Current API specs live under `api/openapi/`
- Archived root-era API snapshots live under `api/openapi/archive/`
- Historical delivery metadata and milestone files live under `docs/root/archive/manifests/`

## Notes
- Root documentation was cleaned up; historical and migrated root documents were moved under `docs/root/`
- Doctrine entities and migrations are the primary schema path; legacy SQL assets are no longer the primary operational flow
