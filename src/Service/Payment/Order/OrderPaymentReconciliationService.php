<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Payment\Order;

use App\Entity\Order\OrderEntity;
use App\Repository\Order\OrderRepository;
use App\ServiceInterface\Payment\Order\OrderPaymentReconciliationServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

final readonly class OrderPaymentReconciliationService implements OrderPaymentReconciliationServiceInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
    ) {
    }

    public function onCaptured(string $orderId, string $paymentId, int $amountMinor, string $currency): void
    {
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($orderId);
        if (!$order) {
            $this->logger->warning('Order not found for payment capture', ['orderId' => $orderId]);

            return;
        }
        $order->markPaid($paymentId, $amountMinor, $currency);
        $this->em->flush();
        $this->logger->info('Order marked as paid', ['orderId' => $orderId, 'paymentId' => $paymentId]);
    }

    public function onRefunded(string $orderId, string $paymentId, int $amountMinor, string $currency): void
    {
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $order = $repo->findByIdentifier($orderId);
        if (!$order) {
            $this->logger->warning('Order not found for refund', ['orderId' => $orderId]);

            return;
        }
        $order->markRefunded($paymentId, $amountMinor, $currency);
        $this->em->flush();
        $this->logger->info('Order marked as refunded', ['orderId' => $orderId, 'paymentId' => $paymentId]);
    }
}
