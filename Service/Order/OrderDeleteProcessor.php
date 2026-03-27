<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Message\Order\OrderCancelCommand;
use App\ServiceInterface\Order\OrderDeleteProcessorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderDeleteProcessor implements ProcessorInterface, OrderDeleteProcessorInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $id = $uriVariables['id'] ?? null;
        if ($id) {
            $this->bus->dispatch(new OrderCancelCommand($id));
        }

        return null;
    }
}
