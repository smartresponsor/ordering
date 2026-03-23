<?php

declare(strict_types=1);

namespace App\Message\Handler\Order;

use App\Message\Command\StartOrderSagaCommand;

final class RecalculateOrderPricingHandler
{
    public function __invoke(object $message): void
    {
        if ($message instanceof StartOrderSagaCommand) {
            return;
        }
    }
}
