<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderRefundTransaction;
use App\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderRefundTransactionRepository implements OrderRefundTransactionRepositoryInterface
{
    /** @var list<OrderRefundTransaction> */
    private static array $transactions = [];

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function add(OrderRefundTransaction $tx): void
    {
        self::$transactions[] = $tx;
        $this->em->persist($tx);
    }

    public function sumByOrder(string $orderId): string
    {
        $sum = '0.00';

        foreach (self::$transactions as $tx) {
            if ($tx->orderId() !== $orderId) {
                continue;
            }

            $sum = bcadd($sum, $tx->amount(), 2);
        }

        return $sum;
    }
}
