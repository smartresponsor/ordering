# Ordering runtime boot audit — wave 21

## Focus
- Read-model placeholder repair
- Event/message DTO normalization
- Event subscriber restoration for read-model projection

## Repaired files
- `src/ReadModel/Entity/OrderView.php`
- `src/ReadModel/Entity/OrderCustomerOrdersView.php`
- `src/ReadModel/Subscriber/OrderEventProjectorSubscriber.php`
- `src/ReadModel/Command/SyncReadModelsCommand.php`
- `src/Message/OrderEventMessage.php`
- `src/Api/DTO/OrderInput.php`
- `src/Event/Order/OrderPlacedEvent.php`
- `src/Event/Order/OrderShippedEvent.php`

## Effect
- removed comment-only placeholders from read-model entity/subscriber/command layer
- restored concrete subscriber implementation behind `config/services/read_model.yaml`
- normalized several short runtime DTO/event/message files to canonical PHP header order
