# Order — Deploy Full Stack

## Quick start (local)
```bash
cp .env.local.example .env.local
make up
make test-mail
```

## Namespaces & Security
Apply namespaces with PSA labels (and SCC for OpenShift if needed):
```bash
kubectl apply -f deploy/security/namespace-staging.yaml
kubectl apply -f deploy/security/namespace-prod.yaml
# OpenShift:
kubectl apply -f deploy/security/securitycontextconstraints.yaml
```

## Helmfile
```bash
helmfile -f deploy/deploy/order/deploy/deploy/order/helmfile.yaml apply -e staging
helmfile -f deploy/deploy/order/deploy/deploy/order/helmfile.yaml apply -e production
```

## CI/CD
See workflows under `.github/workflows`. Rollback and Slack dual-env included.
