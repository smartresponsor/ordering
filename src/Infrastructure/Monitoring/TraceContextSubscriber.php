<?php

declare(strict_types=1);

namespace App\Infrastructure\Monitoring;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final readonly class TraceContextSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly string $serviceName = 'order-component',
        private readonly string $responseHeaderName = 'X-Trace-Id',
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['onRequest', 256],
            ResponseEvent::class => ['onResponse', -256],
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $incomingTraceId = $request->headers->get($this->responseHeaderName)
            ?? $request->headers->get('traceparent');

        $traceId = is_string($incomingTraceId) && '' !== trim($incomingTraceId)
            ? trim($incomingTraceId)
            : $this->generateTraceId();

        $request->attributes->set('_trace_id', $traceId);
        $request->attributes->set('_trace_service', $this->serviceName);
    }

    public function onResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $traceId = $request->attributes->get('_trace_id');
        if (!is_string($traceId) || '' === $traceId) {
            return;
        }

        $response = $event->getResponse();
        if ($response instanceof Response) {
            $response->headers->set($this->responseHeaderName, $traceId);
            $response->headers->set(
                'X-Trace-Service',
                (string) $request->attributes->get('_trace_service', $this->serviceName),
            );
        }
    }

    private function generateTraceId(): string
    {
        return bin2hex(random_bytes(16));
    }
}
