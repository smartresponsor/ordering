Order structure status

This repo contains multiple generations of scaffolding. This file documents the current structural baseline after the structure-normalization pass.

- Runtime entrypoint is Symfony Runtime (public/index.php + vendor/autoload_runtime.php).
- Kernel is src/Kernel.php (App\Kernel).
- Bundles are defined in config/bundles.php.
- Composer autoload root is App\ => src/.

Known remaining structural debt (to be handled in the next structure iteration):
- Duplicate test roots: test/ and tests/ must be consolidated.
- Many phpunit tests currently live under src/** and should be moved under test/.
- Legacy micro-entrypoint is preserved at legacy/entrypoint/public-index-micro.php.
- Legacy composer.lock snapshot is preserved at legacy/composer/composer.lock.prev.
