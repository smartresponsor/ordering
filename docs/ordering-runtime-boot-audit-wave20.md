# Ordering runtime / boot audit — wave 20

Wave 20 addressed concrete boot-shape problems in the current cumulative slice:

- `config/routes/order.yaml` referenced `App\Controller\OrderController::health` and `::getById`, while the controller only exposed `__invoke`-style business actions. Compatibility wrapper methods were added.
- `src/ReadModel/Api/Controller/CustomerOrdersViewAction.php` was a comment-only placeholder and could not satisfy route/controller registration.
- `src/ReadModel/Repository/OrderReadRepository.php` was a comment-only placeholder and could not satisfy service registration.
- `src/ReadModel/Service/OrderReadModelProjector.php` was a comment-only placeholder and could not satisfy service registration.

Wave 20 therefore strengthens route/container coherence without relying on older slices.
