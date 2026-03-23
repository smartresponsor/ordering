<?php

declare(strict_types=1);

namespace App\MessageHandler\Order;

use App\Message\Order\OrderRefundCommand;
use App\Service\Order\RefundService;
use App\Service\Order\TransactionalEventPublisher;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderRefundCommandHandler
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
