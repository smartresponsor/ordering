<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Security\Order;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Ordering\ApiResource\View\Order\OrderResource;
use App\Ordering\Message\Command\Order\OrderCancelCommand;
use App\Ordering\Message\Command\Order\OrderPaymentCommand;
use App\Ordering\Message\Command\Order\OrderShipmentCommand;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OrderPatchProcessor implements ProcessorInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): OrderResource
    {
        \assert($data instanceof OrderResource);
        $id = $uriVariables['id'] ?? $data->id ?? null;
        if (!$id) {
            return $data;
        }

        // РїСЂРѕСЃС‚Р°СЏ РјР°СЂС€СЂСѓС‚РёР·Р°С†РёСЏ РїРѕ СЃС‚Р°С‚СѓСЃСѓ/РїРѕР»СЏРј
        if (($data->status ?? null) === 'cancelled') {
            $this->bus->dispatch(new OrderCancelCommand($id));
        }
        if (($data->paidTotal ?? null) !== null) {
            $this->bus->dispatch(new OrderPaymentCommand($id, $data->paidTotal));
        }
        if (($data->status ?? null) === 'shipped') {
            $carrier = $context['carrier'] ?? 'manual';
            \assert(\is_string($carrier) && '' !== $carrier);
            $this->bus->dispatch(new OrderShipmentCommand($id, $carrier));
        }

        return $data;
    }
}
