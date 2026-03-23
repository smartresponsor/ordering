# Ordering runtime boot audit — wave 13

## Findings

This wave repaired the remaining provider/client files that still referenced legacy `SmartResponsor\...` namespaces.

## Repaired files

- `src/Service/Order/OrderClient.php`
- `src/Service/Order/ProviderRouter.php`
- `src/Service/Order/RouterTest.php`
- `src/ServiceInterface/Order/OrderClientInterface.php`
- `src/ServiceInterface/Order/ProviderRouterInterface.php`

## Added local support types

- `src/Service/Order/HttpClientInterface.php`
- `src/Service/Order/ProviderAdapterInterface.php`
- `src/Service/Order/StripeAdapter.php`
- `src/Service/Order/DummyAdapter.php`
