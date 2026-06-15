<?php

declare(strict_types=1);

namespace App\Controller\Monitoring;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Routing\Attribute\Route;

final readonly class OrderReadinessController
{
    public function __construct(private TransportInterface $asyncTransport)
    {
    }

    #[Route('/readiness', name: 'readiness', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $ok = method_exists($this->asyncTransport, 'get') || method_exists($this->asyncTransport, '__toString');

        return new JsonResponse(['status' => $ok ? 'ready' : 'degraded'], $ok ? 200 : 503);
    }
}
