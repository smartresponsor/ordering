<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order;
use App\Entity\Order\OrderReturnPolicy;
use App\Entity\Order\OrderShipment;
use App\ServiceInterface\Order\OrderRefundEligibilityServiceInterface;
use App\ServiceInterface\Order\RefundEligibilityServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final class RefundEligibilityService implements RefundEligibilityServiceInterface, OrderRefundEligibilityServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function canRefund(Order $order): bool
    {
        // If no delivered shipments -> allow (pre-delivery policy handled elsewhere)
        /** @var OrderShipment[] $ships */
        $ships = $this->em->getRepository(OrderShipment::class)->findBy(['order' => $order]);
        $delivered = array_filter($ships, fn ($s) => OrderShipment::STATUS_DELIVERED === $s->getStatus() || OrderShipment::STATUS_COMPLETED === $s->getStatus());
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
