# SmartResponsor / Order Component — Phase III Orchestration Fast-Import Guide

## 1. Purpose
This guide formalizes how to continue the Order component’s development history using the Fast-Import conveyor.
Phase I–II (legacy → alpha → beta → RC) are already fully integrated into git.
Phase III begins a new chronological line of orchestration commits (v1.1.x → v2.x).

---

## 2. Current Repository Baseline

| Branch | Role |
|-------|------|
| `order_legacy_history` | Historic Phase I (imported alpha iterations). Read-only. |
| `order_migration_stage` | Baseline snapshot after migration. |
| `feature/order_refactor` | Active Phase II development (beta → RC → release). |
| `order_orchestration` | **New Phase III branch** for future fast-import commits. |

Create the orchestration branch:

```bash
git checkout feature/order_refactor
git pull
git checkout -b order_orchestration
git push origin order_orchestration
```

---

## 3. Author Identity

```text
author Oleksandr Tishchenko <dev@smartresponsor.com>
committer Oleksandr Tishchenko <dev@smartresponsor.com>
```

> Keep these consistent across fast-import files to ensure clean history attribution.

---

## 4. Commit Contract for Fast Import

Each archive (`001_order-<concise-summary>.zip`) represents **one commit**.
The concise summary also serves as the commit message stem (lowercase, hyphenated).

**Naming progression examples:**
```
001_order-signal-bridge-setup.zip
002_order-federation-sync-adapter.zip
003_order-governance-workflow-hooks.zip
```

**Template block (for `fast_import_order_phase3.txt`):**
```text
commit refs/heads/order_orchestration
committer Oleksandr Tishchenko <dev@smartresponsor.com> 1733778000 +0000
author Oleksandr Tishchenko <dev@smartresponsor.com> 1733778000 +0000
data <<COMMIT_MESSAGE
order: signal bridge setup
COMMIT_MESSAGE
M 100644 inline order/README.md
data <<INLINE_FILE
# Order Orchestration — Iteration 001
## Summary
Signal Bridge setup phase. Establishes foundational orchestration layer between
Order domain services and cross-domain Federation signals.

## Notes
- Introduces core "SignalBridge" entity and integration interface.
- Provides adapter hooks for Vendor and Billing domains.
- Sets up scaffolding for asynchronous event routing.
INLINE_FILE
```

> ⏱ Dates (`1733778000`) can be shifted forward by randomized intervals to simulate human activity.

---

## 5. Generating and Applying Fast-Import Files

1. **Compose a new import file** (per iteration or in small batches):
   - `fast_import_order_phase3.txt`

2. **Apply it safely** on the orchestration branch:
```bash
git checkout order_orchestration
git fast-import < fast_import_order_phase3.txt
```

3. **Verify the imported history:**
```bash
git log --oneline --graph --decorate
```

4. **Tag the milestone:**
```bash
git tag -a v1.1.0-alpha -m "Order Orchestration Alpha start"
git push --tags
```

---

## 6. ZIP Archive Contract

| Field  | Description |
|-------|-------------|
| Prefix | Sequential 3-digit number (`001`, `002`, …). |
| Domain | Always `order` (no plural). |
| Summary | Auto-generated short slug derived from commit message. |
| Suffix (optional) | Phase tag (`-alpha`, `-beta`, `-rc`, `-release`). |

**Examples:**
```
001_order-signal-bridge-setup.zip
002_order-governance-ui-adapter.zip
003_order-observability-metrics.zip
```

---

## 7. Best Practices for Phase III

- One iteration → one archive → one commit.
- Maintain strict chronology and incremental prefixes.
- Do not reuse archive numbers across domains.
- Use semantic commit messages: `order: <action/area>`.
- Run `git tag -a vX.Y.Z-phase3-checkpoint` after each major iteration.
- Include a brief `CHANGELOG.md` snippet in each archive root.

---

## 8. Troubleshooting

**Re-run imports without duplication**
- If a fast-import was applied to the wrong branch, create a new branch at the prior good commit and re-apply.
- Use `git reflog` to find the pre-import state and `git reset --hard` if necessary (only if no shared pushes).

**Common errors**
- `error: Missing space before < in author ident`: Ensure the author line is exactly `Name <email>`.
- `fatal: Empty data for file`: Ensure `data <<INLINE_FILE` blocks have matching terminators.

---

## 9. Next Steps

After creating the `order_orchestration` branch and committing the first new iteration via fast-import,
Phase III officially begins.

When you are ready for automated packaging, request generation of the next
`fast_import_order_phase3.txt` with the desired iteration name and files to inline or add.
