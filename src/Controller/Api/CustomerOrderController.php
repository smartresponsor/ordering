<?php

declare(strict_types=1);

namespace App\Ordering\Controller\Api;

use App\Ordering\ReadModel\ServiceInterface\CustomerOrderReadServiceInterface;
use App\Ordering\ReadModel\View\CustomerOrderDetail;
use App\Ordering\ReadModel\View\CustomerOrderItemSummary;
use App\Ordering\ReadModel\View\CustomerOrderSummary;
use App\Ordering\Service\CustomerOrderActorAccessService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final readonly class CustomerOrderController
{
    public function __construct(
        private CustomerOrderReadServiceInterface $orders,
        private CustomerOrderActorAccessService $actors,
    ) {
    }

    #[Route('/api/customer/order', name: 'ordering_customer_order_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $customerId = $this->actors->requireCustomerId($request);

        return new JsonResponse([
            'data' => array_map($this->summary(...), $this->orders->listForCustomer($customerId)),
        ]);
    }

    #[Route('/api/customer/order/{reference}', name: 'ordering_customer_order_detail', methods: ['GET'])]
    public function detail(Request $request, string $reference): JsonResponse
    {
        $customerId = $this->actors->requireCustomerId($request);
        $order = $this->orders->findDetailForCustomer($customerId, $reference);
        if (!$order instanceof CustomerOrderDetail) {
            throw new NotFoundHttpException('Order not found.');
        }

        return new JsonResponse([
            'data' => [
                'reference' => $order->reference,
                'number' => $order->number,
                'status' => $order->status,
                'currency' => $order->currency,
                'grandTotal' => $order->grandTotal,
                'createdAt' => $order->createdAt,
                'updatedAt' => $order->updatedAt,
                'items' => array_map(static fn (CustomerOrderItemSummary $item): array => [
                    'reference' => $item->reference,
                    'quantity' => $item->quantity,
                    'currency' => $item->currency,
                    'unitPrice' => $item->unitPrice,
                ], $order->items),
            ],
        ]);
    }

    /** @return array<string, string> */
    private function summary(CustomerOrderSummary $order): array
    {
        return [
            'reference' => $order->reference,
            'number' => $order->number,
            'status' => $order->status,
            'currency' => $order->currency,
            'grandTotal' => $order->grandTotal,
            'createdAt' => $order->createdAt,
            'updatedAt' => $order->updatedAt,
        ];
    }
}
