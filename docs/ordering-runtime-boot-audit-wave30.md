# Ordering runtime boot audit — wave 30

This wave repairs doctrine migrations boot coherence.

## Findings addressed
- `migrations/Version202510062126_baseline.php` had a PHP parse error in `getDescription()` and a malformed `down()` body.
- `config/packages/doctrine_migrations.php` only registered `App\\Migrations`, while the current slice contains migration namespaces across several trees, including `DoctrineMigrations`, `OrderComponent\\Order\\Migrations`, and `OrderComponent\\Migrations\\Order`.

## Result
- The known migration parse blocker is removed.
- Doctrine migrations config now recognizes the namespace families present in the current slice.
