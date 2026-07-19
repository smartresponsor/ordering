<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Refund\Order;

use App\Model\Order\OrderReturnPolicy;
use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderShipmentEntity;
use App\ServiceInterface\Refund\Order\OrderRefundEligibilityServiceInterface;
use App\ServiceInterface\Refund\Order\RefundEligibilityServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class RefundEligibilityService implements RefundEligibilityServiceInterface, OrderRefundEligibilityServiceInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function canRefund(OrderEntity $order): bool
    {
        // If no delivered shipments -> allow (pre-delivery policy handled elsewhere)
        /** @var OrderShipmentEntity[] $ships */
        $ships = $this->em->getRepository(OrderShipmentEntity::class)->findBy(['order' => $order]);
        $delivered = array_filter($ships, fn ($s) => OrderShipmentEntity::STATUS_DELIVERED === $s->getStatus() || OrderShipmentEntity::STATUS_COMPLETED === $s->getStatus());
        if (!$delivered) {
            return true;
        }
        // Check return policy for latest delivered
        usort($delivered, fn ($a, $b) => ($a->getDeliveredAt()?->getTimestamp() ?? 0) <=> ($b->getDeliveredAt()?->getTimestamp() ?? 0));
        $last = end($delivered);
        if (!$last || !$last->getDeliveredAt()) {
            return true;
        }
        $policy = $this->em->getRepository(OrderReturnPolicy::class)->findOneBy(['shipment' => $last]);
        if (!$policy) {
            // default window 14 days
            $expire = $last->getDeliveredAt()->modify('+14 days');

            return $expire > new \DateTimeImmutable('now');
        }
        $exp = $policy->getAutoExpireDate();

        return !$exp || $exp > new \DateTimeImmutable('now');
    }
}
