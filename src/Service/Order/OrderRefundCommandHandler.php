<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Message\Order\OrderRefundCommand;
use App\ServiceInterface\Order\OrderRefundCommandHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderRefundCommandHandler implements OrderRefundCommandHandlerInterface
{
    public function __construct(private RefundService $service, private TransactionalEventPublisher $publisher)
    {
    }

    public function __invoke(OrderRefundCommand $cmd): void
    {
        $tx = $this->service->refund($cmd->orderId, $cmd->amount, $cmd->reason);
        $this->publisher->publish('order.refunded', ['orderId' => $cmd->orderId, 'amount' => $cmd->amount, 'refundId' => $tx->refundId()]);
    }
}
