# Payment Router — Health & Quotas

- **HealthProbe (Redis)**: score in [0..1]; degrade on исключениях, improve при успехах; ниже 0.2 — исключить из пула.
- **Quota (Redis, token bucket)**: ключ `quota:<provider>:<tenant>`; лимит RPM; отказ при исчерпании токенов.
- **ProviderRouterPlus**: учитывает health + quota + веса.

Интеграция: создайте Redis клиента, HealthProbe, Quota и передайте в ProviderRouterPlus вместе с массивом провайдеров.
