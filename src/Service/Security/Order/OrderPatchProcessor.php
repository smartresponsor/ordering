<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\Order\OrderResource;
use App\Message\Command\Order\OrderCancelCommand;
use App\Message\Command\Order\OrderPaymentCommand;
use App\Message\Command\Order\OrderShipmentCommand;
use App\ServiceInterface\Security\Order\OrderPatchProcessorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OrderPatchProcessor implements ProcessorInterface, OrderPatchProcessorInterface
{
    public function __construct(private readonly MessageBusInterface $bus) {
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
            $carrier = $context['carrier'] ?? 'manual';
            assert(\is_string($carrier) && '' !== $carrier);
            $this->bus->dispatch(new OrderShipmentCommand($id, $carrier));
        }

        return $data;
    }
}
