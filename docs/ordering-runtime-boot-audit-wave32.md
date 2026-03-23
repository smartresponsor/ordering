# Ordering runtime/boot audit — wave 32

Wave 32 focuses on PSR-4 and bundle boot coherence in the current slice.

## Repaired items
- `src/OrderComponentBundle.php` now declares `App\OrderComponentBundle`.
- `src/DependencyInjection/OrderComponentExtension.php` now declares `App\DependencyInjection\OrderComponentExtension`.
- Remaining test namespace/file-path mismatches under `tests/` were normalized to `Tests\...`.

## Result
- Bundle/extension class names now match their file names.
- Remaining detected `tests/` namespace/file-path PSR-4 mismatches from this scan are closed.
