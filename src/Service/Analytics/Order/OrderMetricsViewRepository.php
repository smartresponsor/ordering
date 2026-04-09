<?php

declare(strict_types=1);

namespace App\Service\Analytics\Order;

use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderMetricsViewRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function findByVendorId(string $vendorId): ?OrderMetricsView
    {
        return $this->em->getRepository(OrderMetricsView::class)->findOneBy(['vendorId' => $vendorId]);
    }

    public function upsert(string $vendorId, callable $mutator): OrderMetricsView
    {
        $view = $this->findByVendorId($vendorId) ?? new OrderMetricsView($vendorId);
        $mutator($view);
        $view->updatedAt = new \DateTimeImmutable('now');
        $this->em->persist($view);
        $this->em->flush();

        return $view;
    }
}
