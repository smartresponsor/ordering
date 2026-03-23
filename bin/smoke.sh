#!/usr/bin/env bash
set -euo pipefail
BASE="${BASE:-http://localhost:8080}"
echo "Create:"
curl -sS -X POST "$BASE/api/v1/orders" -H "Content-Type: application/json" -d '{"totalAmount":1999,"currency":"USD","customerId":"cus_1"}' | tee /tmp/order.json
id=$(jq -r .id </tmp/order.json)
echo "Get:"
curl -sS "$BASE/api/v1/orders/$id" | jq .
echo "Transition:"
curl -sS -o /dev/null -w "%{http_code}
" -X POST "$BASE/api/v1/orders/$id/transitions/confirm" -H "Idempotency-Key: smk-1"
