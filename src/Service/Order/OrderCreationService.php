<?php

declare(strict_types=1);

namespace App\Ordering\Service\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\ServiceInterface\Order\OrderCreationServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderCreationService implements OrderCreationServiceInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function create(string $customerId, string $vendorId, string $currency, string $grandTotal): OrderEntity
    {
        $customerId = trim($customerId);
        $vendorId = trim($vendorId);
        $currency = strtoupper(trim($currency));
        $grandTotal = trim($grandTotal);

        if ('' === $customerId) {
            throw new \InvalidArgumentException('Customer identifier is required.');
        }
        if ('' === $vendorId) {
            throw new \InvalidArgumentException('Vendor identifier is required.');
        }
        if (3 !== strlen($currency) || !ctype_alpha($currency)) {
            throw new \InvalidArgumentException('Currency must be a three-letter code.');
        }
        if (!is_numeric($grandTotal) || (float) $grandTotal < 0) {
            throw new \InvalidArgumentException('Grand total must be a non-negative amount.');
        }

        $order = new OrderEntity($currency, $grandTotal);
        $order->setCustomerId($customerId);
        $order->setVendorId($vendorId);
        $order->place();

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}
