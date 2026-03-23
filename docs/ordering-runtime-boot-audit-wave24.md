# Ordering runtime boot audit - wave 24

## Focus
- isolate embedded test files under `src/` from runtime namespace pollution
- switch PHPUnit bootstrap to `tests/bootstrap.php`
- harden bootstrap around Doctrine presence

## Findings addressed
- embedded `src/**/*Test.php` files were declared under `App\...`
- phpunit bootstrap was still using `vendor/autoload.php` directly
- schema bootstrap assumed `EntityManagerInterface` was always available
