<?php

declare(strict_types=1);

namespace App\Infrastructure\Order\Observability;

use App\Service\Order\Observability\MonologApiMetric;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class ApiMetricListener
{
    public function __construct(private readonly MonologApiMetric $metric)
    {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();

        $this->metric->record([
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'status' => $response->getStatusCode(),
        ]);
    }
}
