<?php

declare(strict_types=1);

namespace App\Ordering\Service\Analytics\Order;

use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderMetricsRollupViewRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findOne(string $vendorId, string $periodType, string $periodValue): ?OrderMetricsRollupView
    {
        return $this->em->getRepository(OrderMetricsRollupView::class)->findOneBy([
            'vendorId' => $vendorId, 'periodType' => $periodType, 'periodValue' => $periodValue,
        ]);
    }

    public function upsert(string $vendorId, string $periodType, string $periodValue, callable $mutator): OrderMetricsRollupView
    {
        $view = $this->findOne($vendorId, $periodType, $periodValue)
            ?? new OrderMetricsRollupView($vendorId, $periodType, $periodValue);
        $mutator($view);
        $view->recomputeLtv();
        $view->updatedAt = new \DateTimeImmutable('now');
        $this->em->persist($view);
        $this->em->flush();

        return $view;
    }

    /** @return OrderMetricsRollupView[] */
    public function listByVendorAndPeriod(string $vendorId, ?string $periodType = null): array
    {
        $criteria = ['vendorId' => $vendorId];
        if ($periodType) {
            $criteria['periodType'] = $periodType;
        }

        return $this->em->getRepository(OrderMetricsRollupView::class)->findBy($criteria, ['periodValue' => 'ASC']);
    }
}
