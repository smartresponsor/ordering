<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Analytics\Order;

use App\Ordering\Entity\Order\OrderMetricsProjectionEntity;
use App\ServiceInterface\Analytics\Order\MetricsProjectionServiceInterface;
use App\ServiceInterface\Analytics\Order\OrderMetricsProjectionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class MetricsProjectionService implements MetricsProjectionServiceInterface, OrderMetricsProjectionServiceInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    private function getOrCreateDay(\DateTimeImmutable $day): OrderMetricsProjectionEntity
    {
        $repo = $this->em->getRepository(OrderMetricsProjectionEntity::class);
        $existing = $repo->findOneBy(['date' => $day]);
        if ($existing) {
            return $existing;
        }

        $obj = new OrderMetricsProjectionEntity($day);
        $this->em->persist($obj);

        return $obj;
    }

    private function getOrCreateVendor(string $vendorId, \DateTimeImmutable $day): VendorRevenueViewEntity
    {
        $repo = $this->em->getRepository(VendorRevenueViewEntity::class);
        $existing = $repo->findOneBy(['vendorId' => $vendorId, 'date' => $day]);
        if ($existing) {
            return $existing;
        }

        $obj = new VendorRevenueViewEntity($vendorId, $day);
        $this->em->persist($obj);

        return $obj;
    }

    private function getOrCreateRefundDay(\DateTimeImmutable $day): RefundStatsViewEntity
    {
        $repo = $this->em->getRepository(RefundStatsViewEntity::class);
        $existing = $repo->findOneBy(['date' => $day]);
        if ($existing) {
            return $existing;
        }

        $obj = new RefundStatsViewEntity($day);
        $this->em->persist($obj);

        return $obj;
    }

    public function projectOrderPlaced(string $orderId, string $amount, ?string $vendorId, \DateTimeImmutable $at): void
    {
        $day = new \DateTimeImmutable($at->format('Y-m-d'));
        $this->em->wrapInTransaction(function () use ($day, $amount, $vendorId): void {
            $d = $this->getOrCreateDay($day);
            $d->addOrder($amount);
            if (null !== $vendorId) {
                $v = $this->getOrCreateVendor($vendorId, $day);
                $v->addOrder($amount);
            }
        });
    }

    public function projectRefund(string $amount, \DateTimeImmutable $at): void
    {
        $day = new \DateTimeImmutable($at->format('Y-m-d'));
        $this->em->wrapInTransaction(function () use ($day, $amount): void {
            $d = $this->getOrCreateDay($day);
            $d->addRefund($amount);
            $r = $this->getOrCreateRefundDay($day);
            $r->addRefund($amount);
        });
    }
}
