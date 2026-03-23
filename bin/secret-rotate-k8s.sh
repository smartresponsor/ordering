#!/usr/bin/env bash
set -euo pipefail
# usage: SECRET=my-secret KEY=stripe_webhook_secret VALUE=whsec_xxx NAMESPACE=default ./bin/secret-rotate-k8s.sh
SECRET="${SECRET:?missing}"
KEY="${KEY:?missing}"
VALUE="${VALUE:?missing}"
NAMESPACE="${NAMESPACE:-default}"
kubectl -n "$NAMESPACE" patch secret "$SECRET" --type merge -p "{"data": {"$KEY": "$(printf %s "$VALUE" | base64)"}}"
kubectl -n "$NAMESPACE" rollout restart deploy/order-api || true
kubectl -n "$NAMESPACE" rollout restart deploy/order-worker || true
echo "secret rotated and rollout triggered"
