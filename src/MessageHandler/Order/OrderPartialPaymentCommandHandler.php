<?php

declare(strict_types=1);

namespace App\MessageHandler\Order;

use App\Message\Order\OrderPartialPaymentCommand;
use App\Service\Order\Adapter\Payment\PaymentGatewayInterface;
use App\Service\Order\PartialPaymentService;
use App\Service\Order\TransactionalEventPublisher;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPartialPaymentCommandHandler
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
