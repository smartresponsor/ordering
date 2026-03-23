# Ordering runtime boot audit — wave 23

## Finding
The test tree had a namespace/autoload mismatch:

- Composer dev autoload: `Tests\ => tests/`
- Multiple test files declared `namespace App\Tests\...`

This wave normalizes those declarations to `Tests\...` and adds `tests/Kernel.php` as a lightweight wrapper over `App\Kernel` for files that import `Tests\Kernel`.

## Verification
- `php -l` passes on:
  - `tests/Kernel.php`
  - `tests/Integration/Kernel.php`
  - `tests/Functional/Kernel.php`
  - `tests/E2E/Kernel.php`
  - representative repaired test files such as `tests/Integration/DoctrineMappingTest.php`

## Residual note
This wave targets test/bootstrap autoload coherence. It does not claim the full test suite is green; runtime wiring and fixture assumptions may still need later waves.
