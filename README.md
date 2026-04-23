# Ordering

Symfony-oriented Order component focused on order lifecycle, payments, shipments, refunds, workflows, API exposure, outbox processing, observability, and operational delivery assets.

## Root policy
The repository root stays reserved for active runtime and build surfaces:
- application/runtime entry points: `src/`, `config/`, `bin/`, `templates/`, `migrations/`, `public`-style assets where applicable
- build and QA tooling: `composer.json`, `phpunit.xml*`, `phpstan.neon*`, `rector.neon`, `qodana.yaml`, `Makefile`
- delivery and deployment surfaces: `docker/`, `deploy/`, `charts/`, `helm/`, `k8s/`, `security/`, `observability/`, `ops/`
- canonical human entry point: this `README.md`

Historical notes, release fragments, wave reports, and auxiliary root readmes were moved under `docs/root/` to reduce root noise without changing runtime logic.

## Documentation map
- `docs/root/README.md` — index of relocated root documentation
- `docs/` — technical guides, runtime audits, and component notes
- `deploy/` — deployment-oriented assets and deployment-specific readmes
- `docker/` — container runtime assets
- `observability/` — Grafana and Prometheus assets

## Main operational paths
- `src/` — Symfony application code
- `config/` — framework and component configuration
- `bin/` — console and smoke/demo entry points
- `tests/` — automated coverage
- `deploy/` — deployment bundle and Helm/namespace references
- `docker/` — Docker and container support files
- `docs/` — technical and historical documentation

## Notes
- Runtime logic was intentionally left in place.
- This cleanup is root-focused and documentation-focused.
- Where duplicate historical documentation existed at root, the content was relocated rather than discarded.
