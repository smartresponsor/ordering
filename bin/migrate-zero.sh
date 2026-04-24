#!/usr/bin/env bash
set -euo pipefail
DB="${DB:-$DB_URL}"
if [ -z "${DB:-}" ]; then echo "Set DB or DB_URL"; exit 2; fi
echo "[expand]"
psql "$DB" -v ON_ERROR_STOP=1 -f sql/bootstrap/003_expand.sql || true
echo "[migrate] backfill if needed"
# add backfill scripts here
echo "[contract] (deferred)"
echo "Run 004_contract.sql only when safe"
