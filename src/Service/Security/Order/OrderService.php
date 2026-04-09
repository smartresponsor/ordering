<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order;
use App\Entity\Order\IdempotencyKey;
use App\Entity\Order\OrderPayment;
use App\Entity\Order\OrderRefundLedger;
use App\Event\Domain\Order\OrderFullyRefundedEvent;
use App\Event\Domain\Order\OrderPartiallyRefundedEvent;
use App\ServiceInterface\Security\Order\OrderServiceInterface;
use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\RefundAmount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventDispatcherInterface $events,
    ) {
    }

    public function refundPartial(Order $order, Money $amount, string $idempotencyKey): OrderRefundLedger
    {
        $refund = RefundAmount::fromMoney($amount);
        $ledger = new OrderRefundLedger($order, $idempotencyKey, $refund->getAmount(), (string) $refund->getCurrency());
        $this->em->persist($ledger);
        $this->em->persist(new IdempotencyKey('order_refund_'.$order->getId(), hash('sha256', $idempotencyKey)));

        $payments = $this->em->getRepository(OrderPayment::class)->findBy(['order' => $order], ['id' => 'DESC']);
        $left = $refund->getAmount();
        foreach ($payments as $p) {
            if (!$p instanceof OrderPayment) { continue; }
            if (!in_array($p->getStatus(), ['captured', 'paid', 'succeeded'], true)) { continue; }
            $can = bcsub($p->getAmount(), $p->getRefundedAmount(), 2);
            if (bccomp($can, '0.00', 2) <= 0) { continue; }
            $take = 1 === bccomp($left, $can, 2) ? $can : $left;
            if (1 === bccomp($take, '0.00', 2)) {
                $p->addRefundedAmount($take);
                $left = bcsub($left, $take, 2);
            }
            if (0 === bccomp($left, '0.00', 2)) { break; }
        }

        $order->markRefunded($refund->getAmount());
        $this->em->flush();
        $this->events->dispatch(new OrderPartiallyRefundedEvent($order, $refund->getAmount(), (string) $refund->getCurrency()));
        if (0 === bccomp($order->getRefundedTotal(), $order->getPaidTotal(), 2)) {
            $this->events->dispatch(new OrderFullyRefundedEvent($order, $order->getRefundedTotal(), (string) $refund->getCurrency()));
        }
        return $ledger;
    }

    public function payOrder(Order $order): void
    {
        $order->markPaid();
        $this->em->persist($order);
        $this->em->flush();
    }

    public function shipOrder(Order $order, string $carrier = 'DHL'): string
    {
        $tracking = 'TRK-'.substr(str_replace('-', '', $order->getId()), 0, 12);
        $order->assignTracking($tracking);
        $order->markAsShipped();
        $order->ship($carrier, $tracking);
        $this->em->persist($order);
        $this->em->flush();
        return $tracking;
    }

    public function recalcTaxes(Order $order, ?string $countryCode = null): void
    {
        $order->setTaxTotal('0.00');
        $order->setGrandTotal($order->getSubtotal());
        $this->em->persist($order);
        $this->em->flush();
    }

    public function refundOrder(Order $order, float $amount): bool
    {
        $order->refund(number_format($amount, 2, '.', ''));
        $this->em->persist($order);
        $this->em->flush();
        return true;
    }
}
