<?php

declare(strict_types=1);

namespace App\Infrastructure\Monitoring;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class TraceContextSubscriber implements EventSubscriberInterface
{
    private object|null $tracer = null;

    public function __construct(string $serviceName = 'order-component', string $otlpEndpoint = 'http://otel-collector:4318/v1/traces')
    {
        if (!class_exists(\OpenTelemetry\SDK\Trace\TracerProvider::class)
            || !class_exists(\OpenTelemetry\Contrib\Otlp\SpanExporter::class)
            || !class_exists(\OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor::class)
            || !class_exists(\OpenTelemetry\SDK\Resource\ResourceInfo::class)
            || !class_exists(\OpenTelemetry\SDK\Resource\Detectors\SdkProvided::class)
            || !class_exists(\OpenTelemetry\SemConv\ResourceAttributes::class)
            || !class_exists(\OpenTelemetry\API\Trace\SpanKind::class)) {
            return;
        }

        $resource = \OpenTelemetry\SDK\Resource\ResourceInfo::merge(
            \OpenTelemetry\SDK\Resource\ResourceInfo::create([\OpenTelemetry\SemConv\ResourceAttributes::SERVICE_NAME => $serviceName]),
            (new \OpenTelemetry\SDK\Resource\Detectors\SdkProvided())->getResource(),
        );
        $exporter = new \OpenTelemetry\Contrib\Otlp\SpanExporter($otlpEndpoint, null, ['Content-Type' => 'application/x-protobuf']);
        $provider = \OpenTelemetry\SDK\Trace\TracerProvider::builder()
            ->addSpanProcessor(new \OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor($exporter))
            ->setResource($resource)
            ->build();
        $this->tracer = $provider->getTracer('order-component');
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
        if (null === $this->tracer || !is_callable([$this->tracer, 'spanBuilder'])) {
            return;
        }

        $request = $event->getRequest();
        $builder = $this->tracer->spanBuilder($request->getMethod().' '.$request->getPathInfo());
        if (!is_object($builder) || !is_callable([$builder, 'setSpanKind']) || !is_callable([$builder, 'startSpan'])) {
            return;
        }

        $spanKind = defined('OpenTelemetry\\API\\Trace\\SpanKind::KIND_SERVER') ? constant('OpenTelemetry\\API\\Trace\\SpanKind::KIND_SERVER') : 2;
        $span = $builder->setSpanKind($spanKind)->startSpan();
        if (!is_object($span) || !is_callable([$span, 'activate'])) {
            return;
        }

        $scope = $span->activate();
        $request->attributes->set('_otel_span', $span);
        $request->attributes->set('_otel_scope', $scope);
    }

    public function onResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $span = $request->attributes->get('_otel_span');
        $scope = $request->attributes->get('_otel_scope');

        if (!is_object($span) || !is_object($scope) || !is_callable([$scope, 'detach']) || !is_callable([$span, 'end'])) {
            return;
        }

        $response = $event->getResponse();
        if ($response instanceof Response && is_callable([$span, 'setAttribute'])) {
            $span->setAttribute('http.status_code', $response->getStatusCode());
        }

        $scope->detach();
        $span->end();
    }
}
