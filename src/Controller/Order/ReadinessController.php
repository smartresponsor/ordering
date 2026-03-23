<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Routing\Annotation\Route;

final class ReadinessController
{
    public function __construct(private readonly TransportInterface $asyncTransport)
    {
    }

    #[Route('/readiness', name: 'readiness', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $ok = method_exists($this->asyncTransport, 'get') || method_exists($this->asyncTransport, '__toString');

        return new JsonResponse(['status' => $ok ? 'ready' : 'degraded'], $ok ? 200 : 503);
    }
}
