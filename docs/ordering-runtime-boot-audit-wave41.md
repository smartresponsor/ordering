# Ordering runtime boot audit — wave 41

## Focus

Controller/API/repository coherence for pricing convert, returns, and payment projection flow.

## Findings addressed

- `ApiPricingConvertController` imported non-existent pricing/taxation symbols and called a calculator with the wrong shape.
- `ApiReturnController` referenced `ReturnRequestOutput`, which did not exist.
- `OrderSyncPaymentsCommand` and payment projection entity referenced `OrderPaymentViewRepository`, which did not exist.

## Result

- Pricing-convert controller now uses the real `AdvancedPriceCalculator` contract.
- Return output DTO exists and matches controller output.
- Payment view repository exists and is Doctrine-oriented.
