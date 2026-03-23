# Capacity Model (v1)

- API: baseline 200 rps/node at p95 200–250ms; scale at 65% CPU or inflight > 50/pod.
- Worker: baseline 100 jobs/sec/node with backlog-age p95 <= 30s.
- DB: monitor CPU < 70%, p95 query <= 50ms; scale read replicas for /GET.
- Payments: provider quota: Stripe 100 rps; implement backoff and jitter with router.
