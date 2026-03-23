#!/usr/bin/env bash
set -euo pipefail
KUBE="${KUBE:-false}"
if [ "$KUBE" = "true" ]; then
  kubectl delete pod -l app=order-worker --force --grace-period=0 || true
else
  pkill -f "php .*bin/worker.php" || true
fi
echo "Worker killed"
