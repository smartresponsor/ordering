#!/usr/bin/env bash
set -euo pipefail
# Simulate JWKS rotation by bumping cache-buster on URL in env or config map
FILE="${FILE:-config/auth/idp.json}"
ts=$(date +%s)
tmp=$(mktemp)
jq '.issuers |= map(. + {"jwks_cache_buster": "'$ts'"})' "$FILE" > "$tmp" && mv "$tmp" "$FILE"
echo "JWKS rotation simulated (cache-buster=$ts) in $FILE"
