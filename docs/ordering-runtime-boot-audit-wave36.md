# Ordering runtime boot audit — wave 36

## Finding
`config/services/read_model.yaml` still wired `App\ReadModel\Service\OrderReadModelProjector` with two constructor arguments even though the class now declares only one (`EntityManagerInterface`).

## Repair
The service definition was reduced to the single real constructor argument.

## Effect
This removes a concrete Symfony container/service-constructor mismatch in the read-model layer.
