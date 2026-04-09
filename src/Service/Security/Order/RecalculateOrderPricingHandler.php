<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Message\Command\Order\RecalculateOrderPricingCommand;
use App\Service\Pricing\Order\OrderPricingService;
use App\ServiceInterface\Security\Order\RecalculateOrderPricingHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecalculateOrderPricingHandler implements RecalculateOrderPricingHandlerInterface
{
    public function __construct(private readonly OrderPricingService $service) {
    }

    public function __invoke(RecalculateOrderPricingCommand $cmd): void
    {
        $this->service->calculate($cmd->orderId);
    }
}
