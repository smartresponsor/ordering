#!/usr/bin/env bash
set -euo pipefail
# For containerized runs, prefer faketime. Here we just print instructions.
echo "Run container with: docker run --rm --cap-add SYS_TIME -e FAKETIME='+120 sec' <img> ... (using libfaketime)"
