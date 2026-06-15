<?php

declare(strict_types=1);

namespace App\ServiceInterface\State\Order;

use App\Entity\Order\OrderEntity;

interface OrderStateMachineInterface
{
    public function place(OrderEntity $OrderEntity): void;

    public function confirm(OrderEntity $OrderEntity): void;

    public function fulfill(OrderEntity $OrderEntity): void;

    public function close(OrderEntity $OrderEntity): void;

    public function cancel(OrderEntity $OrderEntity): void;

    public function return(OrderEntity $OrderEntity): void;
}
