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
use App\Api\Resource\Order\OrderResource;
use App\Message\Command\Order\OrderCancelCommand;
use App\Message\Command\Order\OrderPaymentCommand;
use App\Message\Command\Order\OrderShipmentCommand;
use App\ServiceInterface\Order\OrderPatchProcessorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderPatchProcessor implements ProcessorInterface, OrderPatchProcessorInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        \assert($data instanceof OrderResource);
        $id = $uriVariables['id'] ?? $data->id ?? null;
        if (!$id) {
            return $data;
        }

        // простая маршрутизация по статусу/полям
        if (($data->status ?? null) === 'cancelled') {
            $this->bus->dispatch(new OrderCancelCommand($id));
        }
        if (($data->paidTotal ?? null) !== null) {
            $this->bus->dispatch(new OrderPaymentCommand($id, $data->paidTotal));
        }
        if (($data->status ?? null) === 'shipped') {
            $this->bus->dispatch(new OrderShipmentCommand($id));
        }

        return $data;
    }
}
