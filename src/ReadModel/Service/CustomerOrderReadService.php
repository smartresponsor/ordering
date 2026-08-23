<?php

declare(strict_types=1);

namespace App\Ordering\ReadModel\Service;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\ReadModel\Repository\OrderReadRepository;
use App\Ordering\ReadModel\ServiceInterface\CustomerOrderReadServiceInterface;
use App\Ordering\ReadModel\View\CustomerOrderDetail;
use App\Ordering\ReadModel\View\CustomerOrderItemSummary;
use App\Ordering\ReadModel\View\CustomerOrderSummary;

final readonly class CustomerOrderReadService implements CustomerOrderReadServiceInterface
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
        $customerId = trim($customerId);
        $orderReference = trim($orderReference);
        if ('' === $customerId || '' === $orderReference) {
            return null;
        }

        foreach ($this->orders->findByCustomerId($customerId) as $order) {
            if ($order->slug() === $orderReference || $order->getNumber() === $orderReference || (string) $order->id() === $orderReference) {
                return $this->summarize($order);
            }
        }

        return null;
    }

    /** @return list<CustomerOrderDetail> */
    public function listDetailsForCustomer(string $customerId): array
    {
        $customerId = trim($customerId);
        if ('' === $customerId) {
            return [];
        }

        return array_map(
            $this->detail(...),
            $this->orders->findByCustomerId($customerId),
        );
    }

    public function findDetailForCustomer(string $customerId, string $orderReference): ?CustomerOrderDetail
    {
        $customerId = trim($customerId);
        $orderReference = trim($orderReference);
        if ('' === $customerId || '' === $orderReference) {
            return null;
        }

        foreach ($this->orders->findByCustomerId($customerId) as $order) {
            if ($order->slug() === $orderReference || $order->getNumber() === $orderReference || (string) $order->id() === $orderReference) {
                return $this->detail($order);
            }
        }

        return null;
    }

    private function detail(OrderEntity $order): CustomerOrderDetail
    {
        $items = [];
        foreach ($order->getItems() as $item) {
            $items[] = new CustomerOrderItemSummary(
                reference: $item->getSku(),
                quantity: $item->getQuantity(),
                currency: $item->getCurrency(),
                unitPrice: $item->getUnitPrice(),
            );
        }

        return new CustomerOrderDetail(
            reference: $order->slug(),
            number: $order->getNumber(),
            status: $order->status(),
            currency: $order->currency(),
            grandTotal: $order->grandTotal(),
            createdAt: $order->getCreatedAt()->format(DATE_ATOM),
            updatedAt: $order->getUpdatedAt()->format(DATE_ATOM),
            items: $items,
        );
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
