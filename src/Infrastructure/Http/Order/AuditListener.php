<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Order;

use App\Service\Http\Order\MonologAuditLogger;
use App\Service\Http\Order\Redactor;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final readonly class AuditListener
{
    /** @var list<string> */
    private array $excludePath;

    /** @param list<string> $excludePath */
    public function __construct(
        private MonologAuditLogger $logger,
        private Redactor $redactor,
        private float $sampleRatio = 1.0,
        private int $maxBodyBytes = 2048,
        array $excludePath = [],
    ) {
        $this->excludePath = array_values($excludePath);
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || !$this->shouldLog($event->getRequest()->getPathInfo())) {
            return;
        }

        $request = $event->getRequest();
        $event->getRequest()->attributes->set('_order_audit_started_at', microtime(true));

        $this->logger->log('request', [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'query' => $this->redactor->redactArray($request->query->all()),
        ]);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest() || !$this->shouldLog($event->getRequest()->getPathInfo())) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();
        $startedAtValue = $request->attributes->get('_order_audit_started_at', microtime(true));
        $startedAt = is_float($startedAtValue) || is_int($startedAtValue) ? (float) $startedAtValue : microtime(true);

        $this->logger->log('response', [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'status' => $response->getStatusCode(),
            'duration_ms' => max(0, (int) round((microtime(true) - $startedAt) * 1000)),
            'sample_ratio' => $this->sampleRatio,
            'max_body_bytes' => $this->maxBodyBytes,
        ]);
    }

    private function shouldLog(string $path): bool
    {
        return array_all($this->excludePath, fn ($prefix) => '' === $prefix || !str_starts_with($path, $prefix));
    }
}
