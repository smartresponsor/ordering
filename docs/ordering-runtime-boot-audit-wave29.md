# Ordering runtime boot audit wave 29

This wave tightens Doctrine/read-model coherence.

## Changes
- `OrderMetricsAggregate` and `OrderMetricsRollup` Doctrine mappings now target `src/Service/Order` with prefix `App\Ordering\Service\Order`, which matches the actual attribute entities present in the current slice.
- `config/services/read_model.yaml` now uses canonical namespace-prefix service discovery for read-model subscribers.

## Rationale
The previous mapping files pointed at `src/ReadModel/OrderMetrics` with prefix `App\ReadModel\OrderMetrics`, while the aggregate/rollup attribute entities currently live under `App\Ordering\Service\Order`.
The previous subscriber service registration used `App\Ordering\ReadModel\Subscriber\*`, which is less coherent than a namespace-prefix resource declaration and can interfere with container discovery.
