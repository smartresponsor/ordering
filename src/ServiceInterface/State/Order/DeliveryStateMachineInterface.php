<?php

declare(strict_types=1);

namespace App\Ordering\ServiceInterface\State\Order;

use App\Model\Order\Delivery;

interface DeliveryStateMachineInterface
{
    public function ship(Delivery $delivery): void;

    public function deliver(Delivery $delivery): void;

    public function markLost(Delivery $delivery): void;

    public function return(Delivery $delivery): void;
}
