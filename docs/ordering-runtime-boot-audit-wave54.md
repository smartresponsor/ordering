# Ordering runtime boot audit вЂ” wave 54

Wave 54 repairs hidden contract mismatches in audit/rate-limit/billing/dispute interface files by importing the actual Symfony, Doctrine, and domain symbols they type against.
It also makes `App\Ordering\Service\Order\DisputeService` explicitly implement `DisputeServiceInterface`.
