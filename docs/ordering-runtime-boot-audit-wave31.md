# Ordering runtime boot audit — wave 31

## Fixed
- Read-model projector now matches subscriber call shape and upserts `OrderView` read-model entities.
- Doctrine mapping for read-model entities now points to the actual attribute entity tree under `src/ReadModel/Entity`.

## Verification
- `php -l` passes on touched PHP files.
