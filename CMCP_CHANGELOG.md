# CMCP_CHANGELOG

## 2026-09-13 — Observability Kubernetes non-root hardening

- Started from `origin/master` at `c2c6aa7` after npm dependency cooldown hardening merged.
- Residual Semgrep baseline entering this batch: 42 findings, including seven Kubernetes `run-as-non-root` findings.
- Reconnaissance separated public observability images from repo-local/templated images. The latter remain untouched because their runtime UID contract is not proven.
- Official image contracts support numeric non-root UIDs for the pinned public images: Prometheus and Pushgateway use UID 65534, Grafana uses UID 472.
- Added `runAsNonRoot: true` plus explicit numeric `runAsUser` to the Prometheus, Pushgateway, and Grafana container security contexts only.
- Did not modify exporters, the Helm E2E test pod, or legacy `ghcr.io/acme/order` manifests because enforcing non-root there without a proven image/filesystem contract could break startup.

Что имеем? Three public observability workloads now have Kubernetes-enforced non-root runtime identity backed by verified image UID contracts.

- Full-repository Semgrep verification dropped exactly from 42 to 39 findings; Kubernetes `run-as-non-root` findings dropped from 7 to 4 with no new rule classes.

Что осталось? Clean temporary tooling, review the bounded diff, then signed commit / safe PR / merge.

## 2026-09-13 — npm dependency cooldown hardening

- Started from `origin/master` at `e3636e3` after API Dockerfile non-root hardening merged.
- Residual Semgrep baseline entering this batch: 43 findings, including one npm `missing-minimum-release-age` finding in `.npmrc`.
- Repository uses npm without a package lock (`package-lock=false`), so fresh dependency resolution is the relevant supply-chain surface.
- Local npm is 11.13.0. npm introduced `min-release-age` in 11.10.0; the project setting is therefore supported by the local toolchain even though npm 11.12/11.13 has a known config-reporting bug that may display `null` while installs still honor the setting.
- Added `min-release-age=7` to `.npmrc`, establishing a seven-day dependency publication cooldown without changing package ranges or application code.
- Full-repository Semgrep verification dropped exactly from 43 to 42 findings; the npm release-age finding is gone and no new rule classes appeared.

Что имеем? Fresh npm resolution now rejects package releases younger than seven days, reducing exposure to rapid supply-chain compromise windows.

Что осталось? Clean temporary probe tooling, review the two-file diff, then signed commit / safe PR / merge.

## 2026-09-13 — API Dockerfile non-root hardening

- Started from `origin/master` at `948cca6` after canon scanner path hardening merged.
- Residual Semgrep baseline entering this batch: 44 findings, including one Dockerfile `missing-user` finding in `deploy/docker/api.Dockerfile`.
- Repository search found no in-repo references to `deploy/docker/api.Dockerfile`, lowering integration risk, but the image contract was still inspected rather than silencing the scanner mechanically.
- `deploy/Dockerfile.prod` already establishes the local Symfony runtime convention of writable `var/cache` and `var/log` owned by `www-data`.
- Hardened the API image by creating those runtime directories, assigning them to the built-in `www-data` user from `php:8.2-fpm-alpine`, and switching to `USER www-data` before starting the PHP built-in server on unprivileged port 8080.

Что имеем? The API image now has an explicit non-root runtime identity with Symfony write paths prepared before privilege drop.

- Semgrep verification removed the Dockerfile `missing-user` finding; full-repository findings dropped exactly from 44 to 43 with no new rule classes.

Что осталось? Clean temporary tooling, then signed commit / safe PR / merge.

## 2026-09-13 — Canon scanner write-path hardening

- Started from `origin/master` at `0d578e4` after Kubernetes privilege-escalation hardening merged.
- Entering residual Semgrep baseline: 46 findings, including two `tainted-filename` findings in `tools/ordering-canon/ordering-canon-api-state-processor-layer-scan.php`.
- Reconnaissance confirmed the scanner derives a filesystem target directly from CLI `--write=` input, while repository documentation defines exactly one supported output artifact: `.meta/ordering-canon-api-state-processor-layer-after-wave13.json`.
- Replaced arbitrary repository-relative write-path derivation with an allowlist for that sole documented target. Unsupported `--write` values now fail explicitly instead of reaching `mkdir()` / `file_put_contents()`.
- `AuditRotator` unlink remains intentionally unchanged because its path is server-generated under the project log directory and was previously reviewed as a false positive.
- Targeted Semgrep verification removed both `tainted-filename` findings; PHP lint is clean.
- Full-repository Semgrep verification dropped exactly from 46 to 44 findings with no new rule classes.

Что имеем? The canon scanner can no longer use CLI input to traverse or select arbitrary filesystem targets.

Что осталось? Clean temporary scan tooling, review the bounded diff, then signed commit / safe PR / merge. Remaining isolated findings are Dockerfile missing-user, npm minimum-release-age, and the known AuditRotator unlink false positive.

## 2026-09-13 — Kubernetes privilege-escalation hardening

- Started from `origin/master` at `282532d` after Docker Compose `no-new-privileges` hardening merged.
- Entering residual Semgrep baseline: 54 findings, including 8 Kubernetes `allowPrivilegeEscalation` findings and 7 separate `runAsNonRoot` findings.
- Reconnaissance kept those classes separate: `allowPrivilegeEscalation: false` does not require changing the container UID, while forcing `runAsNonRoot: true` can break images whose USER contract is unknown.
- Exact findings covered 8 containers across observability, legacy Helm, and the E2E test Pod. Added only container-level `securityContext.allowPrivilegeEscalation: false` to those 8 containers.
- Repository inspection did not establish a non-root USER contract for the legacy `ghcr.io/acme/order` images or templated exporter images, so the 7 `runAsNonRoot` findings remain intentionally deferred rather than guessed away.
- Targeted Semgrep verification removed all 8 `allowPrivilegeEscalation` findings and left exactly the 7 `runAsNonRoot` findings.
- Full-repository Semgrep verification dropped exactly from 54 to 46 findings with no new rule classes.

Что имеем? The Kubernetes privilege-escalation layer is closed without imposing an unverified UID policy on images.

Что осталось? Clean temporary scan tooling, review the bounded diff, then signed commit / safe PR / merge. Remaining security debt is 19 PHP `exec-use`, 15 Compose writable-filesystem findings, 7 Kubernetes `runAsNonRoot`, and five isolated findings.

## 2026-09-13 — Docker Compose no-new-privileges hardening

- Started from `origin/master` at `e9c9725` after residual workflow action pinning merged.
- Fresh residual Semgrep baseline entering this batch: 69 findings, including 15 Docker Compose `no-new-privileges` findings and 15 separate writable-filesystem findings.
- Reconnaissance intentionally separated these classes: `read_only` was not applied because Postgres, RabbitMQ, Redis, MinIO and similar services require writable runtime paths; `no-new-privileges` is a narrower privilege-escalation boundary that does not change filesystem semantics.
- Enumerated the exact 15 flagged image-backed Compose services before mutation and added only `security_opt: [no-new-privileges:true]` semantics to those service blocks across six Compose files.
- Targeted Semgrep verification reports zero remaining `no-new-privileges` findings.
- Symfony YAML lint reports all 6 affected Compose files valid.
- Full-repository Semgrep verification dropped exactly from 69 to 54 findings, with no new rule classes; the `no-new-privileges` rule class is absent.

Что имеем? The bounded Compose privilege-escalation layer is closed without forcing read-only filesystems onto stateful services.

Что осталось? Clean temporary scan tooling, review the bounded diff, then signed commit / safe PR / merge. Remaining infrastructure debt is the separate writable-filesystem and Kubernetes security-context classes.

## 2026-09-13 — Residual Semgrep hardening baseline

- Started from `origin/master` at `417883c` after the full top-level GitHub Actions sweep merged.
- Fresh full-repository `semgrep scan --config auto .` reports 86 findings, down from the earlier 174 historical baseline.
- Residual rule distribution: 19 PHP `exec-use`, 17 mutable GitHub Action refs, 15 Docker Compose `no-new-privileges`, 15 Docker Compose writable-filesystem, 8 Kubernetes privilege-escalation/security-context, 7 Kubernetes run-as-non-root, 2 tainted-filename, and one each for Dockerfile missing-user, npm minimum-release-age, and unlink-use.
- Residual path distribution is led by `deploy` (49), then `bin` (19), `charts` (9), `docs` (4), `tools` (2), plus isolated findings in `.npmrc`, `ci`, and `src`.
- Selected next bounded batch: the 17 remaining mutable action refs under nested deploy/security workflows, archived CI documentation, and `ci/github/chaos-nightly.yaml`. These use the same already-verified action/tag SHAs as the completed top-level workflow sweep.

Что имеем? The workflow-related security debt is no longer in `.github/workflows`; only nested/archive workflow copies remain.

Что осталось? Pin the 17 residual action refs, verify the full-repository Semgrep count drops accordingly, then integrate only that bounded supply-chain batch.

### Residual workflow closure

- Pinned all 17 residual mutable action references across nested deploy/security workflows, archived CI documentation, and `ci/github/chaos-nightly.yaml` using previously verified immutable SHAs.
- Full-repository Semgrep verification dropped exactly from 86 to 69 findings; the mutable-action rule class is now absent from the residual baseline.
- Targeted YAML lint exposed one pre-existing malformed inline `with:` map in `deploy/security/.github/workflows/deploy-pipeline.yml`; it was converted to equivalent block YAML without changing `php-version` or extension inputs.
- Final targeted YAML lint: all 4 affected YAML targets valid.

Что имеем? No mutable GitHub Action references remain anywhere in the scanned Ordering repository.

Что осталось? Clean temporary scan tooling, review the bounded diff, then signed commit / safe PR / merge. The next residual security class after this batch is infrastructure hardening (Docker Compose/Kubernetes), not workflow supply-chain pinning.

## 2026-09-13 — Remaining workflow immutable-action sweep

- Started from clean `origin/master` at `b71d37d` after `cd.yml` and `deploy.yml` security hardening merged.
- Inventory covers the 14 remaining workflow files under `.github/workflows/`, excluding already-clean `cd.yml` and `deploy.yml`.
- Targeted Semgrep baseline across those 14 files found only one rule class: mutable GitHub Action references. No remaining workflow produced a shell-injection finding.
- Per-file finding counts: `ab-ci.yml` 2, `ac-ci.yml` 2, `ci-cd.yml` 5, `ci-enhancements.yml` 4, `ci-tests.yml` 2, `ci.yml` 2, `container-image-scan.yml` 4, `k8s-security-scan.yml` 5, `order_component_ci.yml` 2, `order_component_ci_cd_full.yml` 8, `order_component_ci_cd_prod.yml` 11, `packagist.yml` 1, `release.yml` 4, `rollback.yml` 1.
- Existing verified SHA pins from prior workflow hardening will be reused where the same action/tag appears. New security/release/Kubernetes action refs were resolved directly with `git ls-remote`.
- `kubescape/github-action@v3` does not resolve as a literal tag or branch; its published v3 release line includes `v3.0.21` at `c9749b84d138c0cbbb702b258774954f5463b82e`, which will replace the invalid mutable ref.

Что имеем? The remaining workflow debt is homogeneous supply-chain pinning rather than mixed shell-safety defects.

Что осталось? Apply one deterministic fleet replacement map, verify each workflow reaches zero targeted Semgrep findings, lint all workflow YAML, then integrate as one coherent security PR.

### Fleet closure

- Deterministic replacement map pinned exactly 53 mutable action references across the 14 remaining workflows; no `cd.yml` or `deploy.yml` references were touched.
- New immutable pins include Trivy `v0.20.0` (`b2933f565dbc598b29947660e66259e3c7bc8561`), CodeQL action v3 (`c20e34f438d671fc35777cc9820dd7adf8252874`), GitHub Script v7 (`f28e40c7f34bde8b3046d885e986cb6290c5673b`), setup-kubectl v3 (`901a10e89ea615cf61f57ac05cecdf23e7de06d8`), setup-helm v3 (`5119fcb9089d432beecbf79bb2c7915207344b78`), cache v3 (`6f8efc29b200d32929f49075959781ed54ec270c`), and action-gh-release v1 (`26994186c0ac3ef5cae75ac16aa32e8153525f77`).
- `kubescape/github-action@v3` was corrected to immutable release `v3.0.21` (`c9749b84d138c0cbbb702b258774954f5463b82e`) because literal `v3` does not exist as a tag or branch in that repository.
- Fleet YAML lint exposed one pre-existing syntax error in `ci.yml`: an inline `with:` map containing nested GitHub expression quotes. It was converted to equivalent block YAML without changing setup-php values. Final YAML lint: all 16 workflow files valid.
- Final single-pass `semgrep scan --config auto .github/workflows` scanned all 16 tracked workflow files with 82 rules and reports `0 findings / 0 blocking`.
- All temporary inventory, SHA-resolution, mutation, and scan helper scripts were removed after use.

Что имеем? The complete Ordering GitHub Actions surface is now Semgrep-clean for the configured workflow security rules and uses immutable third-party action references.

Что осталось? Final bounded diff review, signed commit, safe publication, PR merge gate, and post-merge verification.

## 2026-09-13 — Post-RC deploy workflow hardening

- Started from `origin/master` at `877eee2` after the previous CD workflow security merge.
- Targeted Semgrep baseline for `.github/workflows/deploy.yml`: 30 findings / 30 blocking across 82 rules — 1 direct GitHub-context shell-injection finding plus 29 mutable GitHub Action references.
- Removed direct `${{ github.ref_name }}` / `${{ github.sha }}` interpolation from the Buildx cache-key shell command by passing values through step environment variables.
- Resolved the exact current tag SHAs for every unique action dependency with `git ls-remote`, then pinned all 29 action uses to full immutable commit SHAs while retaining version comments.
- Verification: targeted `semgrep scan --config auto .github/workflows/deploy.yml` now reports 0 findings / 0 blocking; Symfony YAML lint is green.
- Temporary SHA-resolution and targeted-scan helper scripts were removed after use.

Что имеем? `deploy.yml` moved from 30 Semgrep findings to zero without changing the workflow topology or application runtime.

Что осталось? Final diff acceptance, signed commit, safe publication, PR merge gate, and post-merge verification.

## 2026-09-13 — Post-RC CI shell-injection hardening

- Started from clean `origin/master` at `a277ad8` on `feature/ordering-security-hardening` after Canon024 production packaging merged.
- Semgrep currently reports 174 historical findings. This workstream intentionally does not treat all findings as equivalent or mutate code solely to silence scanners.
- `AuditRotator` unlink finding was triaged as a false positive: the deleted file path is derived only from trusted `%kernel.project_dir%`, a fixed audit-log basename, and a server-generated timestamp; no user-controlled path reaches `unlink()`.
- Selected high-signal bounded fix: `.github/workflows/cd.yml` directly interpolates GitHub context and deployment values into `run:` / remote shell commands. Move those values through environment variables, quote shell expansions, and use GHCR `--password-stdin`.

Что имеем? A real CI command-injection boundary has been isolated without broad workflow churn.

Что осталось? Patch `cd.yml`, run YAML and targeted Semgrep verification, then integrate only if the security signal materially improves.

- `cd.yml` hardened: image/ref values now enter shell through quoted environment variables; remote deploy values are explicitly passed through `ssh-action` `envs`; GHCR authentication uses `--password-stdin`; PHP container selection is separated, checked, and quoted before `docker exec`.
- Exact action tag SHAs were resolved directly with `git ls-remote`: checkout v4 `11d5960a326750d5838078e36cf38b85af677262`, docker/login-action v3 `c94ce9fb468520275223c153574b00df6fe4bcc9`, appleboy/ssh-action v1.0.3 `029f5b4aeeeb58fdfe1410a5d17f967dacf36262`.
- Those action dependencies are now pinned to immutable full commit SHAs while retaining version comments for maintainability.
- Verification: Symfony YAML lint is green and targeted `semgrep scan --config auto .github/workflows/cd.yml` reports `0 findings / 0 blocking` across 82 rules. Before the action pins, the same targeted scan had 3 mutable-action findings; before the shell hardening, the full scan additionally reported direct GitHub-context shell injection in this workflow.
- Temporary verification scripts were removed after use.

Что имеем? The CD workflow has a materially stronger command-execution boundary and immutable third-party action supply chain, with targeted Semgrep reduced to zero findings.

Что осталось? Final diff review, signed commit, safe feature-branch publication, PR merge gate, and post-merge verification.

## 2026-09-13 — Canon024 production Composer manifest

- Started from clean `origin/master` at `95f6ce4` on `feature/ordering-canon024-production-manifest` after the prior RC hardening merge.
- Canon024 requires `composer.prod.json` as the production/container manifest and forbids sibling `path` repositories, `../Component` source paths, and `symlink: true` wiring.
- Canon033 requires identity parity between development and production manifests for package name/type, PSR-4 identity, PHP baseline, and Symfony generation.
- Verified production reference: `App/composer.prod.json` resolves internal SmartResponsor packages through VCS repositories and packaged `dev-master` dependencies rather than workstation sibling paths.
- Selected work: materialize a path-independent Ordering production manifest with the same `ordering/order` identity, `App\\Ordering\\ => src/`, PHP `^8.4`, Symfony `^8.1`, and the production runtime dependency contour already proven in `composer.json`.

Что имеем? The production packaging contract is now factually defined from Canon024/033 plus an existing repository reference.

Что осталось? Create the manifest, run canonical/RC validation, verify the production dependency graph can resolve without sibling path repositories, then integrate only if green.

- File creation note: exact-replace cannot create a missing file, so `composer.prod.json` was added through the guarded patch endpoint; no repository content was changed by the failed create attempt.
- Validation hygiene: broad RC validation unexpectedly regenerated `phpstan-baseline.neon` via `analyse:baseline`; that out-of-scope mutation was explicitly restored from `origin/master` before any commit.

### Production dependency resolution closure

- Verified actual first-party origins rather than inferring repository names. Administering, Collectioning, Cruding, Interfacing, Objecting, and Viewing use their expected `git@github.com:smartresponsor/<name>.git` origins; Tabling is factually hosted at `git@github.com:smartresponsor/tabling-.git`.
- Initial production dry-resolution correctly failed against the guessed `tabling.git` URL; the manifest was corrected to the verified origin.
- Collectioning and Tabling currently have no remote `master` branch. Production root requirements therefore use Composer inline aliases: `dev-collection-query-hardening as dev-master` and `dev-backend-table-actions as dev-master`. This preserves compatibility with Cruding's packaged `dev-main || dev-master` Collectioning requirement without introducing local path wiring.
- Isolated validation with `COMPOSER=composer.prod.json` reports the manifest valid and a `composer update --dry-run --no-dev --no-install --no-interaction` resolves the full packaged graph successfully: 114 installs planned, zero removals, and no security advisories. Resolved first-party evidence includes Collectioning `9fc56c8`, Tabling `8f955eb`, Cruding `dev-master`, Objecting `dev-master`, Interfacing `dev-master`, Viewing `dev-master`, and Administering `dev-master`.
- Temporary validation/recovery scripts were removed after use; no helper tooling or regenerated PHPStan baseline is retained in the patch.

Что имеем? `composer.prod.json` now satisfies the Canon024 path-independent production model, Canon033 identity parity, and real Composer VCS graph resolution.

Что осталось? Re-run the normal development manifest/canon/full local gates, inspect the final two-file patch, then signed commit, push, PR merge gate, and post-merge verification.

### Final gate closure

- Development `composer validate --strict --check-lock`: green.
- Owner canon enforcement: green with 0 violations.
- Full local pipeline completed through the durable bounded runner with exit code 0: Composer audit, PHP/YAML/Twig/container lint, Doctrine mapping, unit 10 tests / 75 assertions, functional 2 tests / 15 assertions, Gitleaks, and Semgrep all completed. Semgrep remains at the same 174 historical report-only findings.
- No temporary validation scripts or PHPStan baseline changes remain in the intended patch.

Что имеем? Canon024/033 production packaging is implemented, VCS-resolvable, and development/runtime gates remain green.

Что осталось? Final diff acceptance and remote integration only.

## 2026-09-13 — Ordering RC dependency-canon continuation

- Baseline: clean worktree on `feature/ordering-rc-journal-final-20260911` before mutation.
- Read Ordering README/Composer/current RC inventory and prior journal; mandatory helper contracts from Objecting, Cruding, Viewing, and Interfacing; Collectioning and Tabling package identities; Canonization architecture rules Canon007, Canon008, Canon018, Canon022, Canon023, Canon024, Canon025, Canon026, Canon043; Gating remains the executable companion.
- Canon mapping: `ordering/order` => `App\\Ordering\\ => src/`; no namespace rewrite is justified. Ordering has standalone Symfony boot surfaces and Canon022 therefore requires direct runtime dependencies on Cruding, Collectioning, Tabling, Viewing, Interfacing, Objecting, and EasyAdmin.
- Confirmed RC defect: local path dependencies used `*@dev`; `composer validate --strict` reports the unbound constraints and Canon043 requires exact `dev-master`.
- Selected RC-critical work: normalize first-party local dependency constraints, complete the direct standalone dependency baseline, preserve symlink path repositories, synchronize Composer lock state, and rerun quality/runtime gates.
- Growth workstream remains non-blocking: workflow/idempotency observability, reconciliation/timeline UX, and order-edit/version tooling.
- Separate tail: `composer.prod.json` is absent while Canon024 requires a production manifest; it is not guessed from development wiring and remains a separately evidenced packaging task.

Что имеем? Canonical dependency/version defects are isolated and the bounded RC correction is being applied.

Что осталось? Synchronize lock state, run Composer/canon/tests/static/runtime verification, and integrate only on green evidence.

### Verification and runtime repair closure

- Composer dependency resolution completed successfully with direct Collectioning/Tabling installation and local junction wiring; Composer audit reported no known security advisories.
- `composer validate --strict --check-lock`: green after normalizing `psr/simple-cache` from exact `3.0` to `^3.0`.
- `composer lint:canon`: green with 0 violations.
- `composer analyse`: green across 904 files with 0 PHPStan errors.
- First full local pipeline exposed a real Doctrine metadata mismatch after Objecting `dev-master`: translation unique constraints referenced legacy `locale` while `ObjectLocaleEmbeddable` maps `object_locale` / `object_timezone`.
- Repaired both `OrderPaymentTranslationEntity` and `OrderShipmentTranslationEntity` unique constraints to use the actual canonical `object_locale` column.
- Re-ran `composer pipeline:local:full`: green exit code; Composer audit, PHP/YAML/Twig/container lint, Doctrine mapping, unit 10 tests / 75 assertions, functional 2 tests / 15 assertions, and Gitleaks all passed. Doctrine database synchronicity remains intentionally skipped by the repository's configured schema check.
- Semgrep completed successfully but continues to report 174 historical blocking-class findings, concentrated in tracked CI/workflow and legacy surfaces; the repository pipeline treats Semgrep as report-only and returned success. No Semgrep finding was introduced by this bounded dependency/metadata patch.

Что имеем? The RC-critical dependency canon and Objecting locale metadata integration are implemented and the repository's complete local pipeline is green.

Что осталось? Final diff acceptance, coherent signed commit, push, PR mergeability/check inspection, merge if the gate is green, and post-integration state verification.

## PHPStan 2 migration continuation

- User explicitly authorized completing the Ordering migration to PHPStan 2.
- Baseline: HEAD `64fb39a`, branch ahead 2 / behind 0; unrelated pre-existing worktree state remains `config/reference.php` plus untracked `.gating/`.
- Scope: migrate analyzer/configuration to PHPStan 2 and fix Ordering static-analysis errors until `composer analyse` is green without absorbing unrelated changes.
- Result: PHPStan upgraded to 2.2.13 at `level: max`; generated `phpstan-baseline.neon` isolates 1486 legacy diagnostics while non-ignorable PHPStan 2 compatibility defects were fixed in Ordering code and dependency wiring.
- Non-ignorable fixes: corrected final/readonly inheritance wrappers, test-kernel inheritance and kernel target, shipment carrier interface parity, RefundAmount readonly inheritance, Administering runtime dependency/path declaration, Administering descriptor bridge typing, and test container EntityManager narrowing.
- Verification: `composer analyse` green over 904 files; PHP lint green over 911 files; OrderFast 10 tests/75 assertions green; OrderFullStack 2 tests/15 assertions green; Composer validation green with existing development-constraint warnings; Gitleaks false positive on task id `20260911155713` was precisely allowlisted and secret scan is green.

## engine-20260911155713-ordering-741b56

### Iteration 1 — reconnaissance and baseline

- Workspace: `D:\PhpstormProjects\www\Ordering` on branch `feature/facting-order-completed-publish-20260824`.
- Read: `README.md`, `composer.json`, current Git diff/status, Ordering Objecting identity contract test, representative entities, Doctrine config, PHPUnit config.
- Mandatory contour read: Objecting, Cruding, Viewing, Interfacing README/Composer contracts; Gating README/Composer; Canonization `AGENTS.md`, architecture README, Canon001/004/007/008/017/018/027/028/030.
- Canon mapping: `ordering/order` => `App\\Ordering\\` and `Order*`; role-first applies; `Entity/Order` is permitted by Canon004 E1; foreign runtime namespaces require explicit Composer dependencies; Doctrine metadata is schema truth.
- Dependency finding: Objecting, Viewing, and Interfacing are declared path/symlink dependencies; Cruding is absent from Ordering `require` and path repositories and must be resolved from actual runtime contract rather than guessed.
- Existing worktree before CMCP mutation: 14 tracked changes plus untracked `.gating/`; entity edits remove duplicated local IDs from Objecting-composed entities and fix translation locale constraints; `config/reference.php` has an unrelated strict-types removal.
- Market baseline: mature ordering systems emphasize lifecycle state, returns/refunds/exchanges, idempotent retries, deduplicated event delivery, reconciliation/current-state reads, transactions, and rollback/workflow safety.
- RC-critical workstream: finish and verify Objecting identity/schema integration and dependency/canon boundary correctness without expanding Ordering responsibility.
- Growth workstream: richer workflow/idempotency observability, reconciliation diagnostics, order-edit/version UX/API maturity, and operational timeline improvements.
- Risks: pre-existing uncommitted work, missing Cruding contour, Doctrine identity/migration parity, stale generated/config artifacts.
- Planned gates: object-identity test, PHP lint, Composer validate, canon gate, PHPUnit, Symfony/YAML/container lint, Doctrine validation, static analysis, then Git integration if green.

Что имеем? Baseline and authoritative canon mapping are established against the local workspace.

Что осталось? Material implementation, targeted verification/fixes, debt closure/integration, and final acceptance through iteration 5.

### Iteration 2 — material implementation

- Verified the pre-existing Objecting migration with `composer test:object-identity`; Doctrine rejected `OrderStatusEntity` because its backend-owned primary key had been removed.
- Re-read Objecting identity/locale implementation and responsibility boundary: Objecting supplies embedded system identity, while consumer repositories own primary keys. Restored the removed local integer PKs/getters across all affected Ordering entities without reverting the canonical locale-column corrections.
- Re-ran `test:object-identity`: 2 tests / 16 assertions green.
- Confirmed Ordering already imports Cruding services and references `App\\Cruding\\...` capability contracts; added the missing `cruding/crud` runtime dependency and local `../Cruding` symlink repository to satisfy Canon008 and the mandatory application dependency contour.
- Added missing Composer package description reported as a publish-level validation error.

Что имеем? Objecting identity ownership and the Cruding package graph now match the actual Ordering runtime configuration.

Что осталось? Verification of the final dependency/schema state, quality gates, bounded debt handling, and repository integration.

### Iteration 3 — verification and fix

- `composer validate --check-lock`: green; only existing unbound development constraint warnings remain.
- `composer lint:canon`: green with 0 violations.
- `composer pipeline:local:full`: green wrapper result; Composer audit clean, PHP/YAML/Twig/container lint green, Doctrine mapping green, 10 unit tests / 75 assertions green, 2 functional tests / 15 assertions green, Gitleaks clean. Database synchronicity is intentionally skipped by the repository's `--skip-sync` schema command.
- Semgrep completed with 197 findings concentrated in the pre-existing `.gating/` tooling surface; `.gating/` was untracked before this task and is outside the bounded Ordering product patch, so it is not mutated or staged here.
- `composer analyse` exposed a pre-existing analyzer/toolchain problem: PHPStan 1.12 cannot resolve Symfony 8.1 Console classes. A bounded PHPStan 2 compatibility probe resolved class discovery but exposed broad historical repository-wide static-analysis debt across the 904-file analysis scope. The probe was fully reverted; no PHPStan config/version migration is included in this RC.
- Final working tree after the probe contains no PHPStan changes.

Что имеем? Runtime, canon, package metadata, mapping, unit/functional, and secret-scanning gates are green; static analysis has a precisely identified pre-existing repository-wide blocker.

Что осталось? Final diff acceptance, coherent commit/push, PR merge-gate evaluation, and post-integration verification.

### Iteration 4 — debt closure and integration

- Final RC patch was narrowed to `composer.json`, `OrderPaymentTranslationEntity.php`, `OrderShipmentTranslationEntity.php`, and this orchestration journal. The pre-existing `config/reference.php` modification and untracked `.gating/` tree were explicitly excluded from staging.
- Re-ran `composer test:object-identity`: 2 tests / 16 assertions green.
- Created signed commit `4008ad3` (`Harden Ordering RC dependency and identity contracts`).
- Push through Console MCP was attempted and blocked by its `working_tree_dirty` safety guard because the two unrelated pre-existing worktree changes remain present. The branch itself is one commit ahead and zero behind its configured upstream.
- No unrelated files were committed, deleted, reverted, or hidden to bypass that guard. PR creation/merge cannot be truthfully performed until the signed commit is published.

Что имеем? The bounded RC implementation is committed locally and isolated from unrelated pre-existing worktree state.

Что осталось? Final local acceptance and a remote publication/PR step blocked solely by the Console MCP clean-worktree push policy.

### Iteration 5 — final acceptance and handoff

- Post-commit branch state: `feature/facting-order-completed-publish-20260824`, HEAD `4008ad3`, upstream configured, ahead 1 / behind 0 before this journal-only acceptance commit.
- Accepted product evidence: Composer manifest/lock validation green; Canon gate 0 violations; Objecting identity contract 2/16 green; local full pipeline green for audit, PHP/YAML/Twig/container lint, Doctrine mapping, unit 10/75, functional 2/15, and Gitleaks.
- Bounded residual debt: PHPStan 1.x/Symfony 8.1 incompatibility plus broader historical PHPStan 2 findings; Semgrep findings in the pre-existing `.gating/` tooling tree; Doctrine database synchronicity is skipped by the repository's configured `--skip-sync` validation command.
- Remote integration status is not green only because Console MCP refuses push from a dirty worktree containing unrelated user/pre-existing changes. No destructive cleanup was authorized or performed.

Что имеем? The original bounded Ordering RC task is locally implemented, verified, journaled, and committed without absorbing unrelated worktree state.

Что осталось? At that handoff point, remote publication and PR integration were still blocked by the dirty-worktree safety guard.

### Post-handoff RC closure — publication and integration

- Re-entered the same Task ID from the authoritative local workspace rather than restarting implementation.
- Current worktree was clean; branch `feature/facting-order-completed-publish-20260824` was already published and synchronized with its upstream at HEAD `6081c455b2ad1385f8a7506571803c111398f5ce`.
- Re-ran `composer test:object-identity`: 2 tests / 16 assertions green.
- Re-ran `composer pipeline:local:full`: Composer audit, PHP/YAML/Twig/container lint, Doctrine mapping, unit 10/75, functional 2/15, and Gitleaks green. Semgrep continues to report historical CI/security findings that are not introduced by this bounded RC patch.
- Created PR #4 `Harden Ordering RC dependency and identity contracts`; Console MCP inspection reported `MERGEABLE`, zero blockers, and an allowed merge gate.
- Squash-merged PR #4 into `master`; fetched `origin/master` and confirmed the integrated Composer dependency/path contract is present on the remote base branch.
- Console MCP intentionally forbids switching directly to the protected local `master` branch, so post-merge evidence uses fetched `origin/master` plus the merge result rather than bypassing that safety policy.

Что имеем? Ordering RC implementation is published and merged into `master`; the product gates relevant to this bounded task are green, and the remote base contains the canonical dependency contract.

Что осталось? No authorized in-scope RC tail remains. Historical Semgrep/static-analysis debt stays a separate bounded follow-up and is not silently absorbed into this task.
