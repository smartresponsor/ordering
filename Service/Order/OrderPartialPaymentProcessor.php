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
use App\Api\Dto\OrderPartialPaymentInput;
use App\Message\Command\Order\OrderPartialPaymentCommand;
use App\ServiceInterface\Order\OrderPartialPaymentProcessorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderPartialPaymentProcessor implements ProcessorInterface, OrderPartialPaymentProcessorInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        /* @var OrderPartialPaymentInput $data */
        $this->bus->dispatch(new OrderPartialPaymentCommand(
            $data->orderId, $data->amountMinor, $data->currency, $data->paymentMethod, $data->idempotencyKey
        ));

        return ['status' => 'accepted'];
    }
}
