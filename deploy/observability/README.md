# Deploy Observability

This directory is the canonical deployment/operations observability surface for Ordering.

## Contents
- `grafana/` — Grafana dashboards consolidated from former root `dashboards/`, `monitoring/grafana/`, and `observability/grafana/`
- `prometheus/` — Prometheus rules, ServiceMonitor assets, and CI scrape configuration

## Notes
- CI exporters now live under `tools/ci/monitoring/`
- Kubernetes autoscaling manifests now live under `deploy/k8s/`
- Legacy OCI helmfile/profile assets were moved under `deploy/legacy/helmfile/`
