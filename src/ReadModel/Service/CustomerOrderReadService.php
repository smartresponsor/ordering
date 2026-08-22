<?php

declare(strict_types=1);

namespace App\Ordering\ReadModel\Service;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\ReadModel\Repository\OrderReadRepository;
use App\Ordering\ReadModel\View\CustomerOrderSummary;

final readonly class CustomerOrderReadService
{
    public function __construct(private OrderReadRepository $orders)
    {
    }

    /** @return list<CustomerOrderSummary> */
    public function listForCustomer(string $customerId): array
    {
        $customerId = trim($customerId);
        if ('' === $customerId) {
            return [];
        }

        return array_map(
            $this->summarize(...),
            $this->orders->findByCustomerId($customerId),
        );
    }

    public function findForCustomer(string $customerId, string $orderReference): ?CustomerOrderSummary
    {
        $orderReference = trim($orderReference);
        if ('' === $orderReference) {
            return null;
        }

        foreach ($this->orders->findByCustomerId(trim($customerId)) as $order) {
            if ($order->slug() === $orderReference || $order->getNumber() === $orderReference || (string) $order->id() === $orderReference) {
                return $this->summarize($order);
            }
        }

        return null;
    }

    private function summarize(OrderEntity $order): CustomerOrderSummary
    {
        return new CustomerOrderSummary(
            reference: $order->slug(),
            number: $order->getNumber(),
            status: $order->status(),
            currency: $order->currency(),
            grandTotal: $order->grandTotal(),
            createdAt: $order->getCreatedAt()->format(DATE_ATOM),
            updatedAt: $order->getUpdatedAt()->format(DATE_ATOM),
        );
    }
}
