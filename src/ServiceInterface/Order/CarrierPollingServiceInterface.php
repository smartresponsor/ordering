<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Repository\Order\OrderShipmentViewRepository;
use App\Service\Order\OrderShipmentProjectionService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

interface CarrierPollingServiceInterface
{
    public function __construct(
        #[TaggedIterator('order.shipment.carrier')] iterable $carriers,
        OrderShipmentProjectionService $projection,
        OrderShipmentViewRepository $repo,
        LoggerInterface $logger,
    );

    public function poll(string $carrierName, string $orderId, string $trackingNumber): bool;
}
