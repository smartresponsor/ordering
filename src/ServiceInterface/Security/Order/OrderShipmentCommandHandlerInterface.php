<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\Message\Command\Order\OrderShipmentCommand;
use App\Service\Messaging\Order\TransactionalEventPublisher;
use App\Service\Shipment\Order\ShipmentService;
use App\ServiceInterface\Shipment\CarrierInterface;

interface OrderShipmentCommandHandlerInterface
{
    public function __construct(
        ShipmentService $service,
        TransactionalEventPublisher $publisher,
        CarrierInterface $carrier,
    );

    public function __invoke(OrderShipmentCommand $cmd): void;
}
