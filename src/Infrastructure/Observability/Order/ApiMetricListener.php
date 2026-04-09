<?php

declare(strict_types=1);

namespace App\Infrastructure\Observability\Order;

use App\Service\Observability\Order\MonologApiMetric;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final readonly class ApiMetricListener
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
