<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Entity\Order\Order;
use App\Entity\Order\OrderRefundLedger;
use App\ValueObject\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

interface OrderServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $events,
    );

    public function refundPartial(Order $order, Money $amount, string $idempotencyKey): OrderRefundLedger;

    public function payOrder(Order $order): void;

    public function shipOrder(Order $order, string $carrier = 'DHL'): string;

    public function recalcTaxes(Order $order, ?string $countryCode = null): void;

    public function refundOrder(Order $order, float $amount): bool;
}
