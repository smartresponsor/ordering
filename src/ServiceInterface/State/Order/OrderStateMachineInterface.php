<?php

declare(strict_types=1);

namespace App\ServiceInterface\State\Order;

use App\Entity\Order;

interface OrderStateMachineInterface
{
    public function place(Order $order): void;

    public function confirm(Order $order): void;

    public function fulfill(Order $order): void;

    public function close(Order $order): void;

    public function cancel(Order $order): void;

    public function return(Order $order): void;
}
