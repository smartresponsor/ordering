# Ordering runtime boot audit — wave 53

Wave 53 fixed a hidden service-contract mismatch in the return-policy layer.

## Findings addressed
- `App\ServiceInterface\Order\ReturnPolicyServiceInterface` previously referenced `DateTimeImmutable` without importing the global class.
- Inside a namespaced interface this resolved to a non-existent `App\ServiceInterface\Order\DateTimeImmutable`.
- `App\Service\Order\ReturnPolicyService` also did not explicitly implement the interface, so the interface and service could drift independently.

## Result
- interface now imports `DateTimeImmutable`
- interface/service `canReturn()` signatures are aligned
- service now formally implements the interface
