<?php

declare(strict_types=1);

namespace App\Ordering\Controller\Monitoring;

use App\Ordering\Infrastructure\Monitoring\MetricsCollector;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class OrderMetricsController
{
    public function __construct(private MetricsCollector $collector)
    {
    }

    #[Route('/api/order/metric', name: 'api_metrics_orders', methods: ['GET'])]
    public function __invoke(): Response
    {
        $content = $this->collector->render();
        if ('' === trim($content)) {
            $content = "# HELP order_component_build_info Build info\n# TYPE order_component_build_info gauge\norder_component_build_info{version=\"0.1.0-alpha\"} 1\n";
        }

        return new Response($content, 200, ['Content-Type' => 'text/plain; version=0.0.4']);
    }

    #[Route('/api/vendor/metric/{vendorId}', name: 'vendor_metrics', methods: ['GET'])]
    public function view(string $vendorId): JsonResponse
    {
        return new JsonResponse([
            'vendorId' => $vendorId,
            'totalOrders' => 0,
            'completedOrders' => 0,
            'cancelledOrders' => 0,
            'totalRevenue' => '0.00',
            'refundedAmount' => '0.00',
            'avgOrderValue' => '0.00',
        ]);
    }

    #[Route('/api/vendor/metric/aggregate/{vendorId}', name: 'vendor_metrics_aggregate', methods: ['GET'])]
    public function aggregate(string $vendorId): JsonResponse
    {
        return new JsonResponse([
            [
                'vendorId' => $vendorId,
                'periodType' => 'month',
                'periodValue' => date('Y-m'),
                'totalOrders' => 0,
                'totalRevenue' => '0.00',
                'refundedAmount' => '0.00',
                'ltv' => '0.00',
            ],
        ]);
    }

    #[Route('/api/vendor/metric/rollup/{vendorId}', name: 'vendor_metrics_rollup', methods: ['GET'])]
    public function rollup(string $vendorId): JsonResponse
    {
        return new JsonResponse([
            [
                'vendorId' => $vendorId,
                'periodType' => 'quarter',
                'periodValue' => date('Y').'-Q'.((int) ceil((int) date('n') / 3)),
                'totalOrders' => 0,
                'totalRevenue' => '0.00',
                'refundedAmount' => '0.00',
                'ltv' => '0.00',
            ],
        ]);
    }
}
