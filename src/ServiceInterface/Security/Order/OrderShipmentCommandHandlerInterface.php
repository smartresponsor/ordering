<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\Contract\Gateway\Order\CarrierInterface;
use App\Message\Command\Order\OrderShipmentCommand;
use App\Service\Security\Order\TransactionalEventPublisher;
use App\Service\Shipment\Order\ShipmentService;

interface OrderShipmentCommandHandlerInterface
{
    public function __construct(
        ShipmentService $service,
        TransactionalEventPublisher $publisher,
        CarrierInterface $carrier,
    );

    public function __invoke(OrderShipmentCommand $cmd): void;
}
