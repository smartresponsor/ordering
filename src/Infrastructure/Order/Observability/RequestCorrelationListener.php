<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Observability;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class RequestCorrelationListener
{
    private static string $correlationId = '';

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $id = (string) ($request->headers->get('X-Correlation-Id') ?? '');
        if ('' === $id) {
            $id = bin2hex(random_bytes(8));
        }

        self::$correlationId = $id;
        $request->attributes->set('correlation_id', $id);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $event->getResponse()->headers->set('X-Correlation-Id', self::$correlationId);
    }

    public static function currentCorrelationId(): string
    {
        return self::$correlationId;
    }
}
