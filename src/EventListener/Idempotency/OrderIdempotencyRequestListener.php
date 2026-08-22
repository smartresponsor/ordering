<?php

declare(strict_types=1);

namespace App\Ordering\EventListener\Idempotency;

use Symfony\Component\HttpKernel\Event\RequestEvent;

final class OrderIdempotencyRequestListener
{
    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $key = $request->headers->get('Idempotency-Key') ?? $request->headers->get('X-Idempotency-Key');

        if (null !== $key && '' !== $key) {
            $request->attributes->set('_order_idempotency_key', $key);
        }
    }
}
