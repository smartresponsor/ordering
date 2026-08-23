<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Payment\Order;

use App\Ordering\Message\Command\Order\OrderPaymentCommand;
use App\Ordering\Service\Messaging\Order\TransactionalEventPublisher;
use App\Ordering\ServiceInterface\Payment\Order\OrderPaymentCommandHandlerInterface;
use App\Ordering\ServiceInterface\Payment\Order\PaymentGatewayInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPaymentCommandHandler implements OrderPaymentCommandHandlerInterface
{
    public function __construct(
        private PaymentService $service,
        private TransactionalEventPublisher $publisher,
        private PaymentGatewayInterface $gateway,
    ) {
    }

    public function __invoke(OrderPaymentCommand $cmd): void
    {
        $txId = $this->gateway->charge($cmd->orderId, $cmd->amount, ['source' => 'api']);
        $this->service->applyPayment($cmd->orderId, $cmd->amount, $txId);
        $this->publisher->publish('order.paid', ['orderId' => $cmd->orderId, 'amount' => $cmd->amount, 'txId' => $txId]);
    }
}
