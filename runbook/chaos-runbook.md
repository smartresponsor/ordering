# Chaos Runbook (addon)
- RetrySpike: check provider latency, enable breaker, verify backoff.
- BreakerOpen: validate provider status, switch to degraded mode, notify.
- DLQRateHigh: inspect poison message signature, rollout fix, requeue strategy.
- BacklogAgeHigh: scale workers, check DB slow queries, apply bulkhead.
