<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\IdempotencyKey;
use App\Entity\Order;
use App\Entity\Order\OrderPayment;
use App\Entity\Order\OrderRefundLedger;
use App\Event\Domain\Order\OrderFullyRefundedEvent;
use App\Event\Domain\Order\OrderPartiallyRefundedEvent;
use App\ServiceInterface\Order\OrderServiceInterface;
use App\ValueObject\Order\Money;
use App\ValueObject\Order\RefundAmount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    public function refundPartial(Order $order, Money $amount, string $idempotencyKey): OrderRefundLedger
    {
        $hasActiveDispute = (int) $this->em->getConnection()->fetchOne(
            "SELECT COUNT(1) FROM order_dispute WHERE order_id = :oid AND status IN ('open','investigating','chargeback_pending')",
            ['oid' => $order->getId()]
        ) > 0;
        if ($hasActiveDispute) {
            throw new \DomainException('Refunds are blocked while dispute is active');
        }

        $refund = RefundAmount::fromMoney($amount);

        $hash = hash('sha256', $idempotencyKey);
        $exists = $this->em->getConnection()->fetchOne('SELECT 1 FROM idempotency_keys WHERE scope = :s AND key_hash = :h', [
            's' => 'order_refund_'.$order->getId(),
            'h' => $hash,
        ]);
        if ($exists) {
            $row = $this->em->getConnection()->fetchAssociative('SELECT amount,currency FROM order_refund_ledger WHERE order_id = :oid AND idempotency_key = :k', [
                'oid' => $order->getId(), 'k' => $idempotencyKey,
            ]);
            if ($row) {
                return new OrderRefundLedger($order, $idempotencyKey, (string) $row['amount'], (string) $row['currency']);
            }
        }

        if ($order->getCurrency() !== $refund->currency) {
            throw new \DomainException('Currency mismatch');
        }

        $paid = (string) $this->em->getConnection()->fetchOne(
            "SELECT COALESCE(SUM(amount),0) FROM order_payment WHERE order_id = :oid AND status IN ('captured','paid','succeeded')",
            ['oid' => $order->getId()]
        );
        $alreadyRefunded = (string) $this->em->getConnection()->fetchOne(
            'SELECT COALESCE(SUM(amount),0) FROM order_refund_ledger WHERE order_id = :oid',
            ['oid' => $order->getId()]
        );
        if (1 === bccomp(bcadd($alreadyRefunded, $refund->amount, 2), $paid, 2)) {
            throw new \DomainException('Refund exceeds paid amount');
        }

        $ledger = new OrderRefundLedger($order, $idempotencyKey, $refund->amount, $refund->currency);

        $this->em->wrapInTransaction(function () use ($order, $ledger, $hash, $refund) {
            $this->em->persist($ledger);
            $this->em->persist(new IdempotencyKey('order_refund_'.$order->getId(), $hash));

            /** @var OrderPayment[] $payments */
            $payments = $this->em->getRepository(OrderPayment::class)->findBy(['order' => $order], ['id' => 'DESC']);
            $left = $refund->amount;
            foreach ($payments as $p) {
                if (!in_array($p->getStatus(), ['captured', 'paid', 'succeeded'], true)) {
                    continue;
                }
                $can = bcsub($p->getAmount(), $p->getRefundedAmount(), 2);
                if (bccomp($can, '0.00', 2) <= 0) {
                    continue;
                }
                $take = 1 === bccomp($left, $can, 2) ? $can : $left;
                if (1 === bccomp($take, '0.00', 2)) {
                    $p->addRefundedAmount($take);
                    $left = bcsub($left, $take, 2);
                }
                if (0 === bccomp($left, '0.00', 2)) {
                    break;
                }
            }

            $this->em->flush();
        });

        $newTotalRefunded = (string) $this->em->getConnection()->fetchOne(
            'SELECT COALESCE(SUM(amount),0) FROM order_refund_ledger WHERE order_id = :oid',
            ['oid' => $order->getId()]
        );
        $this->events->dispatch(new OrderPartiallyRefundedEvent($order, $refund->amount, $refund->currency));
        if (0 === bccomp($newTotalRefunded, $paid, 2)) {
            $this->events->dispatch(new OrderFullyRefundedEvent($order, $newTotalRefunded, $refund->currency));
        }

        return $ledger;
    }

    public function payOrder(Order $order): void
    {
        $this->paymentGateway->initiatePayment(
            $order->getNumber(),
            (float) $order->getTotalAmount(),
            $order->getCurrency()
        );
        if (method_exists($order, 'markAsPaid')) {
            $order->markAsPaid();
        }
        $this->em->flush();
    }

    public function shipOrder(Order $order, string $carrier = 'DHL'): string
    {
        $tracking = $this->shipmentGateway->createShipment($order, $carrier);
        if (method_exists($order, 'assignTracking')) {
            $order->assignTracking($tracking);
        }
        if (method_exists($order, 'markAsShipped')) {
            $order->markAsShipped();
        }
        $this->em->flush();

        return $tracking;
    }

    public function recalcTaxes(Order $order, ?string $countryCode = null): void
    {
        $breakdown = $this->taxationGateway->calculate($order, $countryCode);
        if (method_exists($order, 'setTaxAmount')) {
            $order->setTaxAmount($breakdown->taxAmount);
        }
        if (method_exists($order, 'setTotalAmount')) {
            $order->setTotalAmount($breakdown->total);
        }
        $this->em->flush();
    }

    public function refundOrder(Order $order, float $amount): bool
    {
        return $this->paymentGateway->refundPayment($order->getNumber(), $amount);
    }
}
