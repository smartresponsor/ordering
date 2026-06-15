# Ordering deploy surface

## Quick start (local)
```bash
cp deploy/.env.local.example .env.local
make -f deploy/Makefile up
```

## Namespaces and security
Apply namespace and security manifests from `deploy/security/`:
```bash
kubectl apply -f deploy/security/namespace-staging.yaml
kubectl apply -f deploy/security/namespace-prod.yaml
# OpenShift only:
kubectl apply -f deploy/security/securitycontextconstraints.yaml
```

## Helmfile
```bash
helmfile -f deploy/order/helmfile.yaml apply -e staging
helmfile -f deploy/order/helmfile.yaml apply -e production
```

## Compose and images
Use the compose files and Dockerfiles under `deploy/`:
- `deploy/docker-compose.local.yml`
- `deploy/docker-compose.dev.yml`
- `deploy/docker-compose.prod.yml`
- `deploy/Dockerfile.local`
- `deploy/Dockerfile.prod`

## CI/CD
Repository workflows live under `.github/workflows/`. Historical root-era CI materials are archived under `docs/root/archive/ci/`.
