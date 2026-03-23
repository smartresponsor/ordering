# Ordering runtime boot audit — wave 42

Wave 42 repaired a coherent stale-import cluster in metrics/export/read-model code.

Closed runtime mismatches:
- OrderMetricsExportCommand no longer imports missing App\Analytics\Export symbols.
- OrderPriceController no longer imports missing App\ReadModel\Order\OrderPriceView.
- Metrics projection/query/projector/aggregator files no longer import missing read-model trees when the real classes live in App\Service\Order.
- Metrics projection flow tests now target the same canonical projection class as runtime code.

Verification:
- php -l passed on all touched PHP files.
