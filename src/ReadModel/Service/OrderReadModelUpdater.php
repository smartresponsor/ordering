<?php

declare(strict_types=1);

namespace App\ReadModel\Service;

use App\ReadModel\Entity\OrderView;
use App\ReadModel\ServiceInterface\Order\OrderReadModelUpdaterInterface;
use App\RepositoryInterface\Order\OrderPaymentTransactionRepositoryInterface;
use App\RepositoryInterface\Order\OrderRefundTransactionRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderReadModelUpdater implements OrderReadModelUpdaterInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderPaymentTransactionRepositoryInterface $payments,
        private OrderRefundTransactionRepositoryInterface $refunds,
    ) {
    }

    public function recalc(string $orderId, string $grandTotal): void
    {
        /** @var OrderView|null $view */
        $view = $this->em->getRepository(OrderView::class)->find($orderId);
        if (!$view) {
            return;
        }
        $paid = (float) $this->payments->sumSucceededByOrder($orderId);
        $ref = (float) $this->refunds->sumByOrder($orderId);
        $view->setPaidTotal(number_format($paid, 2, '.', ''));
        $view->setRefundedTotal(number_format($ref, 2, '.', ''));
        $balance = max(0.0, (float) $grandTotal - $paid + $ref); // if refund reduces paid
        $view->setGrandTotal($grandTotal);
        $view->setStatus($balance <= 0.00001 ? 'paid' : $view->getStatus());
        $this->em->flush();
    }
}
