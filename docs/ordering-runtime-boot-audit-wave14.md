# Ordering runtime boot audit — wave 14

This wave focused on concrete boot/test-kernel blockers found in the wave 13 cumulative snapshot.

## Repaired
- `tests/Functional/Kernel.php`: class renamed to `TestKernel` to match test usage.
- `tests/Integration/Kernel.php`: class renamed to `TestKernel` to match test usage.
- `tests/E2E/Kernel.php`: replaced invalid pseudo-PHP/YAML hybrid syntax with a valid Symfony test kernel.
- `config/services.yaml`: excluded embedded `*Test.php`, `*/Tests/*`, and stray nested `Kernel.php` files from runtime service registration.
- `src/Service/Order/Kernel.php`: corrected project/config path resolution.

## Notes
The repository likely still contains deeper runtime/container issues, but this wave removes several immediate boot blockers without relying on older slices.
