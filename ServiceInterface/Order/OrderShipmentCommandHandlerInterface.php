<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use App\Message\Command\Order\OrderShipmentCommand;
use App\Service\Order\Adapter\Shipment\CarrierInterface;
use App\Service\Order\OrderShipmentService;
use App\Service\Order\TransactionalEventPublisher;

interface OrderShipmentCommandHandlerInterface
{
    public function __construct(
        ShipmentService $service,
        TransactionalEventPublisher $publisher,
        CarrierInterface $carrier,
    );

    public function __invoke(OrderShipmentCommand $cmd): void;
}
