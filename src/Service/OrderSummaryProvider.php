<?php

declare(strict_types=1);

namespace App\Service;

use App\Ordering\Entity\Order\OrderEntity;
use App\ServiceInterface\OrderSummaryProviderInterface;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderSummaryProvider implements OrderSummaryProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function fetchPage(
        string $tenantId,
        int $page,
        int $pageSize,
        ?string $status,
        ?string $createdFromIso,
        ?string $createdToIso,
    ): array {
        $page = max(1, $page);
        $pageSize = max(1, min(100, $pageSize));

        try {
            $qb = $this->entityManager->createQueryBuilder()
                ->select('o')
                ->from(OrderEntity::class, 'o')
                ->orderBy('o.createdAt', 'DESC')
                ->addOrderBy('o.id', 'DESC');

            if (null !== $status && '' !== trim($status)) {
                $qb->andWhere('o.status = :status')->setParameter('status', strtolower(trim($status)));
            }

            if (null !== $createdFromIso && '' !== trim($createdFromIso)) {
                $qb->andWhere('o.createdAt >= :createdFrom')->setParameter('createdFrom', new \DateTimeImmutable($createdFromIso));
            }

            if (null !== $createdToIso && '' !== trim($createdToIso)) {
                $qb->andWhere('o.createdAt <= :createdTo')->setParameter('createdTo', new \DateTimeImmutable($createdToIso));
            }

            $total = (int) $this->entityManager->createQueryBuilder()
                ->select('COUNT(o.id)')
                ->from(OrderEntity::class, 'o')
                ->getQuery()
                ->getSingleScalarResult();

            /** @var list<OrderEntity> $orders */
            $orders = $qb
                ->setFirstResult(($page - 1) * $pageSize)
                ->setMaxResults($pageSize)
                ->getQuery()
                ->getResult();
        } catch (TableNotFoundException) {
            return [
                'item' => [],
                'total' => 0,
                'page' => $page,
                'pageSize' => $pageSize,
            ];
        }

        $items = [];
        foreach ($orders as $order) {
            $items[] = [
                'id' => $order->getId(),
                'status' => $order->getStatus(),
                'createdAtIso' => $order->getCreatedAt()->format(DATE_ATOM),
                'totalGross' => (float) $order->getGrandTotal(),
                'currencyCode' => $order->getCurrency(),
                'customerEmail' => $this->customerEmail($order),
            ];
        }

        return [
            'item' => $items,
            'total' => $total,
            'page' => $page,
            'pageSize' => $pageSize,
        ];
    }

    private function customerEmail(OrderEntity $order): ?string
    {
        $customerId = $order->getCustomerId();
        if (null === $customerId) {
            return null;
        }

        $customerId = trim($customerId);
        if ('' === $customerId || !str_contains($customerId, '@')) {
            return null;
        }

        return $customerId;
    }
}
