<?php

declare(strict_types=1);

namespace App\ServiceInterface\Security\Order;

use App\Entity\Order\Delivery;

interface DeliveryStateMachineInterface
{
    public function ship(Delivery $delivery): void;

    public function deliver(Delivery $delivery): void;

    public function markLost(Delivery $delivery): void;

    public function return(Delivery $delivery): void;
}
