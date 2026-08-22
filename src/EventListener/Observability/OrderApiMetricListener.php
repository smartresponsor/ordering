<?php

declare(strict_types=1);

namespace App\Ordering\EventListener\Observability;

use App\Ordering\Service\Observability\Order\MonologApiMetric;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final readonly class OrderApiMetricListener
{
    public function __construct(private MonologApiMetric $metric)
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
