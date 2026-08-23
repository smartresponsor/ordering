# Ordering runtime boot audit вЂ” wave 79

Wave 79 closed a hidden resource/factory/openapi contract cluster.

## Closed items
- Transformer interfaces now import `OrderEntity` and `OrderResource`.
- `OrderFactoryInterface` now imports `App\Ordering\Entity\Order\Order`.
- `OrderOpenApiFactoryInterface` now imports `ApiPlatform\OpenApi\OpenApi`.
- Concrete transformer/factory services now implement canonical interfaces.
- `App\Ordering\OpenApi\OrderOpenApiFactory` now implements the canonical interface.

## Verification
- `php -l` passes on all touched PHP files.
