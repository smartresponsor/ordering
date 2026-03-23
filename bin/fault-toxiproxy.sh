#!/usr/bin/env bash
set -euo pipefail
# Create proxy api->db with 120ms latency and 3% loss
curl -sS -X POST localhost:8474/proxies -H "Content-Type: application/json" -d '{"name":"api_db","listen":"0.0.0.0:8666","upstream":"db:5432"}' || true
curl -sS -X POST localhost:8474/proxies/api_db/toxics -H "Content-Type: application/json" -d '{"name":"latency","type":"latency","stream":"downstream","attributes":{"latency":120,"jitter":20}}'
curl -sS -X POST localhost:8474/proxies/api_db/toxics -H "Content-Type: application/json" -d '{"name":"loss","type":"limit_data","stream":"downstream","attributes":{"bytes":1048576}}' || true
echo "Toxiproxy configured"
