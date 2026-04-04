<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Message\Legacy\Order\OrderPartialPaymentCommand;
use App\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\ServiceInterface\Security\Order\OrderPartialPaymentCommandHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderPartialPaymentCommandHandler implements OrderPartialPaymentCommandHandlerInterface
{
    public function __construct(
        private PartialPaymentService $service,
        private PaymentGatewayInterface $gateway,
        private TransactionalEventPublisher $publisher,
    ) {
    }

    public function __invoke(OrderPartialPaymentCommand $cmd): void
    {
        $txId = $this->gateway->charge($cmd->orderId, $cmd->amount);
        $this->service->applyPartial($cmd->orderId, $cmd->amount, $cmd->method, $txId);
        $this->publisher->publish('order.paid.partial', ['orderId' => $cmd->orderId, 'amount' => $cmd->amount, 'txId' => $txId]);
    }
}
