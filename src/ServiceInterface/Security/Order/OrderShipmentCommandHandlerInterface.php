<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Security\Order;

use App\Ordering\Message\Command\Order\OrderShipmentCommand;
use App\Ordering\Service\Messaging\Order\TransactionalEventPublisher;
use App\Ordering\Service\Shipment\Order\ShipmentService;
use App\Ordering\ServiceInterface\Shipment\CarrierInterface;

interface OrderShipmentCommandHandlerInterface
{
    public function __construct(
        ShipmentService $service,
        TransactionalEventPublisher $publisher,
        CarrierInterface $carrier,
    );

    public function __invoke(OrderShipmentCommand $cmd): void;
}
