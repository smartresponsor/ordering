#!/usr/bin/env bash
set -euo pipefail
IFACE="${IFACE:-eth0}"
DELAY_MS="${DELAY_MS:-150}"
LOSS_PCT="${LOSS_PCT:-2}"
sudo tc qdisc add dev "$IFACE" root netem delay "${DELAY_MS}ms" loss "${LOSS_PCT}%"
echo "Applied netem on $IFACE: ${DELAY_MS}ms, ${LOSS_PCT}% loss"
