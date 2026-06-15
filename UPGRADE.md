# Upgrade notes

## Namespace and integration model
- Current repository code uses the default Symfony application namespace: `App\\...`
- Do not assume a separate package namespace such as `OrderComponent\\...` in this repository snapshot

## Doctrine-first storage
- Doctrine entities under `src/Entity/` and migrations under `migrations/` are the primary schema path
- Prefer `php bin/console doctrine:migrations:migrate` and `php bin/console doctrine:schema:validate`
- Do not treat legacy SQL assets as the primary operational schema flow

## API
- Current API specs live under `api/openapi/`
- Archived API snapshots live under `api/openapi/archive/`

## Deploy surface
- Dockerfiles and Compose files live under `deploy/`
- Helmfile and values live under `deploy/order/`
- Security manifests live under `deploy/security/`

## Testing
- Main automated test surface lives under `tests/`
- Use repository workflows or local PHPUnit runs for validation
