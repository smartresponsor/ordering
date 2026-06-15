# Ordering extras

Additional repository assets:
- `deploy/Dockerfile.local` and `deploy/Dockerfile.prod` — container build entry points
- `deploy/docker-compose.local.yml`, `deploy/docker-compose.dev.yml`, `deploy/docker-compose.prod.yml`, `deploy/docker-compose.stack.yml` — compose entry points
- `Makefile` — top-level helper targets
- `api/openapi/order-v1.json` — current primary OpenAPI specification
- `api/openapi/order-draft.yaml` — draft API specification
- `api/openapi/archive/openapi-alpha-root.json` — archived early root-level OpenAPI snapshot
- `api/openapi/archive/order-rc3-p1p2-openapi.yaml` — archived RC3 P1/P2 OpenAPI snapshot
- `OrderComponent.postman_collection.json` — Postman collection
- `docs/examples.http` — HTTP examples

Quick usage:
1. Import the Postman collection and set `{{baseUrl}}`.
2. Or use `docs/examples.http` with a REST client.
3. Build images from `deploy/`, for example:
   ```bash
   docker build -f deploy/Dockerfile.prod -t ordering:prod .
   ```
