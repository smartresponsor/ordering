#!/usr/bin/env bash
set -euo pipefail
IFACE="${IFACE:-eth0}"
sudo tc qdisc del dev "$IFACE" root || true
echo "Netem cleared on $IFACE"
