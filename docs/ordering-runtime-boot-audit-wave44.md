# Ordering runtime boot audit wave 44

This wave closes a pricing/runtime support cluster from the wave 43 current slice.

Key effects:
- missing pricing support classes now exist under canonical local namespaces
- AdvancedPriceCalculator no longer points at the incompatible nested Currency namespace service
- pricing ServiceInterface contracts now import real runtime symbols
- OrderDataPersister is simplified into a boot-safe local processor shape
- OrderService refund amount import is aligned with the actually present local runtime class
