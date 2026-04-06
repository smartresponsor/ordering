<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderRefundTransaction;
use App\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderRefundTransactionRepository implements OrderRefundTransactionRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}
    public function add(OrderRefundTransaction $tx): void { $this->em->persist($tx); }
    public function sumByOrder(string $orderId): string { return '0.00'; }
}
