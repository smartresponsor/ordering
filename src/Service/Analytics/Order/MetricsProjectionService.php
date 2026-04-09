<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Analytics\Order;

use App\Entity\Order;
use App\ServiceInterface\Analytics\Order\MetricsProjectionServiceInterface;
use App\ServiceInterface\Analytics\Order\OrderMetricsProjectionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class MetricsProjectionService implements MetricsProjectionServiceInterface, OrderMetricsProjectionServiceInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    private function getOrCreateDay(\DateTimeImmutable $day): OrderMetricsProjection
    {
        $repo = $this->em->getRepository(OrderMetricsProjection::class);
        $existing = $repo->findOneBy(['date' => $day]);
        if ($existing) {
            return $existing;
        }

        $obj = new OrderMetricsProjection($day);
        $this->em->persist($obj);

        return $obj;
    }

    private function getOrCreateVendor(string $vendorId, \DateTimeImmutable $day): VendorRevenueView
    {
        $repo = $this->em->getRepository(VendorRevenueView::class);
        $existing = $repo->findOneBy(['vendorId' => $vendorId, 'date' => $day]);
        if ($existing) {
            return $existing;
        }

        $obj = new VendorRevenueView($vendorId, $day);
        $this->em->persist($obj);

        return $obj;
    }

    private function getOrCreateRefundDay(\DateTimeImmutable $day): RefundStatsView
    {
        $repo = $this->em->getRepository(RefundStatsView::class);
        $existing = $repo->findOneBy(['date' => $day]);
        if ($existing) {
            return $existing;
        }

        $obj = new RefundStatsView($day);
        $this->em->persist($obj);

        return $obj;
    }

    public function projectOrderPlaced(Order $order, string $amount, string $vendorId, \DateTimeImmutable $at): void
    {
        $day = new \DateTimeImmutable($at->format('Y-m-d'));
        $this->em->wrapInTransaction(function () use ($day, $amount, $vendorId): void {
            $d = $this->getOrCreateDay($day);
            $d->addOrder($amount);
            $v = $this->getOrCreateVendor($vendorId, $day);
            $v->addOrder($amount);
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
