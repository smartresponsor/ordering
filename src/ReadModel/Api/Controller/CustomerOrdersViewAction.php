<?php

declare(strict_types=1);

namespace App\ReadModel\Api\Controller;

use App\ReadModel\Repository\OrderReadRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

final readonly class CustomerOrdersViewAction
{
    public function __construct(private OrderReadRepository $repository)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        $orders = $this->repository->findByCustomerId($id);

        return new JsonResponse([
            'customerId' => $id,
            'items' => array_map(
                static fn (object $order): array => [
                    'id' => method_exists($order, 'id') ? $order->id() : null,
                    'status' => method_exists($order, 'status') ? $order->status() : null,
                    'currency' => method_exists($order, 'currency') ? $order->currency() : null,
                    'grandTotal' => method_exists($order, 'grandTotal') ? $order->grandTotal() : null,
                ],
                $orders,
            ),
        ]);
    }
}
