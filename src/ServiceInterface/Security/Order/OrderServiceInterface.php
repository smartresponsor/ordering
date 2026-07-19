<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\Entity\OrderRefundLedger;
use App\Ordering\Entity\Order\OrderEntity;
use App\ValueObject\Pricing\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

interface OrderServiceInterface
{
    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $events,
    );

    public function refundPartial(OrderEntity $OrderEntity, Money $amount, string $idempotencyKey): OrderRefundLedger;

    public function payOrder(OrderEntity $OrderEntity): void;

    public function shipOrder(OrderEntity $OrderEntity, string $carrier = 'DHL'): string;

    public function recalcTaxes(OrderEntity $OrderEntity, ?string $countryCode = null): void;

    public function refundOrder(OrderEntity $OrderEntity, float $amount): bool;
}
