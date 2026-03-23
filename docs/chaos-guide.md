# Chaos Guide (v1)

## Fault options
- **netem**: latency/loss on Linux using `tc qdisc` (requires root).
- **toxiproxy**: TCP proxy with controllable latency/loss (Docker-friendly).
- **kill**: stop DB or worker for T seconds.
- **noisy neighbor**: run k6 at higher RPS for tenant_A while measuring tenant_B.
- **jwks rotate**: swap JWKS URL or cache TTL to force refresh.
- **clock skew**: run API container with `--env TZ` or faketime for test only.

## Safety
- Run in staging or disposable env.
- Always `undo` faults with provided scripts.
