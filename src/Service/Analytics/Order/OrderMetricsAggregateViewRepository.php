<?php

declare(strict_types=1);

namespace App\Service\Analytics\Order;

use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderMetricsAggregateViewRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findOne(string $vendorId, string $periodType, string $periodValue): ?OrderMetricsAggregateView
    {
        return $this->em->getRepository(OrderMetricsAggregateView::class)->findOneBy([
            'vendorId' => $vendorId, 'periodType' => $periodType, 'periodValue' => $periodValue,
        ]);
    }

    public function upsert(string $vendorId, string $periodType, string $periodValue, callable $mutator): OrderMetricsAggregateView
    {
        $view = $this->findOne($vendorId, $periodType, $periodValue)
            ?? new OrderMetricsAggregateView($vendorId, $periodType, $periodValue);
        $mutator($view);
        $view->recomputeLtv();
        $view->updatedAt = new \DateTimeImmutable('now');
        $this->em->persist($view);
        $this->em->flush();

        return $view;
    }

    /** @return OrderMetricsAggregateView[] */
    public function listByVendorAndPeriod(string $vendorId, ?string $periodType = null): array
    {
        $criteria = ['vendorId' => $vendorId];
        if ($periodType) {
            $criteria['periodType'] = $periodType;
        }

        return $this->em->getRepository(OrderMetricsAggregateView::class)->findBy($criteria, ['periodValue' => 'ASC']);
    }
}
