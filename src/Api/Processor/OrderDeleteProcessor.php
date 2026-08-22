<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Ordering\Message\Command\Order\OrderCancelCommand;
use Symfony\Component\Messenger\MessageBusInterface;

/** @implements ProcessorInterface<mixed, null> */
final readonly class OrderDeleteProcessor implements ProcessorInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): null
    {
        $idValue = $uriVariables['id'] ?? null;
        $id = is_scalar($idValue) ? trim((string) $idValue) : '';
        if ('' !== $id) {
            $this->bus->dispatch(new OrderCancelCommand($id));
        }

        return null;
    }
}
