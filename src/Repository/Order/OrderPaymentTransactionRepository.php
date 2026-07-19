<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderPaymentTransactionEntity;
use App\Ordering\RepositoryInterface\Order\OrderPaymentTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderPaymentTransactionRepository implements OrderPaymentTransactionRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function add(OrderPaymentTransactionEntity $tx): void
    {
        $this->em->persist($tx);
    }

    public function sumSucceededByOrder(string $orderId): string
    {
        $sum = '0.00';
        $transactions = $this->em->getRepository(OrderPaymentTransactionEntity::class)->findBy(['orderId' => $orderId]);

        foreach ($transactions as $tx) {
            if (!$tx instanceof OrderPaymentTransactionEntity || 'succeeded' !== $tx->status()) {
                continue;
            }

            $sum = bcadd($sum, $tx->amount(), 2);
        }

        return $sum;
    }
}
