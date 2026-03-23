# Ordering runtime boot audit — wave 25

Wave 25 unified the suite kernels around `Tests\Kernel`, which itself extends the canonical `App\Kernel`.

Practical effect:
- Functional, integration, and E2E suites no longer maintain separate bundle lists.
- Test boot now follows the same application kernel path instead of divergent suite-local kernels.
- PHPUnit bootstrap fails early with a precise message when dependencies are not installed.
