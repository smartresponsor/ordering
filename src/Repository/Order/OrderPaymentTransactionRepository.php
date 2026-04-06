<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderPaymentTransaction;
use App\RepositoryInterface\Order\OrderPaymentTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderPaymentTransactionRepository implements OrderPaymentTransactionRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}
    public function add(OrderPaymentTransaction $tx): void { $this->em->persist($tx); }
    public function sumSucceededByOrder(string $orderId): string { return '0.00'; }
}
