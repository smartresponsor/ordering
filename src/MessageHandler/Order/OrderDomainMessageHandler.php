<?php

declare(strict_types=1);

namespace App\MessageHandler\Order;

use App\Message\Order\OrderDomainMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final readonly class OrderDomainMessageHandler implements MessageHandlerInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(OrderDomainMessage $msg): void
    {
        // В реальности: отправка во внешние системы / webhooks / analytics
        $this->logger->info('[OrderDomainMessage] consumed', [
            'topic' => $msg->topic,
            'messageId' => $msg->messageId,
        ]);
    }
}
