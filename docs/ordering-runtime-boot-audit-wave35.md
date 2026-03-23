# Ordering runtime boot audit — wave 35

## Repaired blocker
- `src/Service/Order/FileDlqRepository.php`

## What was fixed
- Replaced Python-style booleans and list operations with valid PHP.
- Normalized file read/write flow for newline-delimited JSON storage.
- Kept the implementation aligned with the existing `DlqRepositoryInterface` shape.

## Verification
- `php -l src/Service/Order/FileDlqRepository.php` — passed
- Full `php -l` sweep over `src`, `tests`, `config`, `migrations` — `0` parse errors
