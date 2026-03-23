# Secret Rotation

- **K8s Secrets**: patch secret then rollout restart deployment/statefulset.
- **JWKS**: rely on IdP rotation; cache TTL 15m; refresh periodically (`make jwks-refresh`).

Steps:
1) Prepare new secret (e.g., STRIPE_WEBHOOK_SECRET).
2) Patch k8s secret; restart workloads.
3) Verify health + webhooks; remove old value.
