<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderRefundTransactionEntity;
use App\Ordering\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderRefundTransactionRepository implements OrderRefundTransactionRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function add(OrderRefundTransactionEntity $tx): void
    {
        $this->em->persist($tx);
    }

    public function sumByOrder(string $orderId): string
    {
        $sum = '0.00';
        $transactions = $this->em->getRepository(OrderRefundTransactionEntity::class)->findBy(['orderId' => $orderId]);

        foreach ($transactions as $tx) {
            if (!$tx instanceof OrderRefundTransactionEntity) {
                continue;
            }

            $sum = bcadd($sum, $tx->amount(), 2);
        }

        return $sum;
    }
}
