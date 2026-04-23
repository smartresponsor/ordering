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
use App\Api\Dto\OrderPartialPaymentInput;
use App\Message\Command\Order\OrderPartialPaymentCommand;
use App\ServiceInterface\Security\Order\OrderPartialPaymentProcessorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OrderPartialPaymentProcessor implements ProcessorInterface, OrderPartialPaymentProcessorInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): array
    {
        /* @var OrderPartialPaymentInput $data */
        $this->bus->dispatch(new OrderPartialPaymentCommand(
            $uriVariables['id'] ?? $uriVariables['orderId'] ?? '', (int) round(((float) $data->amount) * 100), 'USD', 'manual', $data->externalRef
        ));

        return ['status' => 'accepted'];
    }
}
