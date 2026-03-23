# Ordering runtime boot audit — wave 26

## Repaired
- Suite-local TestKernel classes now live in PSR-4-correct files.
- Legacy suite Kernel.php files remain as compatibility wrappers.
- Stale MessengerBundle registration removed from bundles.php and embedded service kernel.

## Result
This reduces two concrete boot risks:
1. autoload failure for Tests\\*\\TestKernel classes
2. missing MessengerBundle class during bundle registration
