<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Monitoring\MetricsRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MetricsController
{
    public function __construct(private readonly MetricsRegistry $metrics)
    {
    }

    #[Route(path: '/metrics', name: 'metrics', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new Response($this->metrics->dump(), 200, ['Content-Type' => 'text/plain; version=0.0.4']);
    }
}
