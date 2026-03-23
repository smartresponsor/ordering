# Ordering runtime boot audit — wave 12

Wave 12 focused on residual runtime/test blockers in the state-machine slice.

Closed:
- duplicate `<?php` open tags removed from remaining affected files
- stale `SmartResponsor\\...` imports removed from repaired state-machine/runtime files
- missing local event/dispatcher/provider contracts added so repaired classes no longer depend on removed legacy namespace trees
- `tests/OrderStateMachineTest.php` aligned to `Tests\\` + `App\\...`

Verified:
- `php -l` passes on all touched files in this wave

Remaining follow-up candidates:
- `src/Service/Order/OrderClient.php`
- `src/Service/Order/ProviderRouter.php`
- `src/Service/Order/RouterTest.php`
