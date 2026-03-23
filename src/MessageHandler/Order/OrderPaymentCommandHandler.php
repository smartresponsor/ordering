<?php

declare(strict_types=1);

namespace App\MessageHandler\Order;

use App\Message\Order\OrderPaymentCommand;
use App\Service\Order\Adapter\Payment\PaymentGatewayInterface;
use App\Service\Order\PaymentService;
use App\Service\Order\TransactionalEventPublisher;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderPaymentCommandHandler
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
