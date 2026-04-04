<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use Monolog\Processor\ProcessorInterface;

final class OrderContextProcessor implements ProcessorInterface
{
    public function __invoke(array $record): array
    {
        $record['extra']['order_component'] = true;

        return $record;
    }
}
