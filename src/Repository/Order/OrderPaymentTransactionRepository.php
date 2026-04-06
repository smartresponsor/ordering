<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderPaymentTransaction;
use App\RepositoryInterface\Order\OrderPaymentTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderPaymentTransactionRepository implements OrderPaymentTransactionRepositoryInterface
{
    /** @var list<OrderPaymentTransaction> */
    private static array $transactions = [];

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function add(OrderPaymentTransaction $tx): void
    {
        self::$transactions[] = $tx;
        $this->em->persist($tx);
    }

    public function sumSucceededByOrder(string $orderId): string
    {
        $sum = '0.00';

        foreach (self::$transactions as $tx) {
            if ($tx->orderId() !== $orderId || 'succeeded' !== $tx->status()) {
                continue;
            }

            $sum = bcadd($sum, $tx->amount(), 2);
        }

        return $sum;
    }
}
