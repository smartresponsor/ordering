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
use App\Api\Dto\OrderRefundInput;
use App\Message\Command\Order\OrderRefundCommand;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderRefundProcessor implements ProcessorInterface
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        /* @var OrderRefundInput $data */
        $this->bus->dispatch(new OrderRefundCommand(
            $data->orderId, $data->amountMinor, $data->currency, $data->reason, $data->paymentRef, $data->idempotencyKey
        ));

        return ['status' => 'accepted'];
    }
}
