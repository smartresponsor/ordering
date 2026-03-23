#!/usr/bin/env bash
set -euo pipefail
KUBE="${KUBE:-false}"
DUR="${DUR:-90}"
if [ "$KUBE" = "true" ]; then
  kubectl scale deployment postgres --replicas=0
  sleep "$DUR"
  kubectl scale deployment postgres --replicas=1
else
  docker compose stop postgres
  sleep "$DUR"
  docker compose start postgres
fi
echo "DB outage simulated for ${DUR}s"
