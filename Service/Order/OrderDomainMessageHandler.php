<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Message\Order\OrderDomainMessage;
use App\ServiceInterface\Order\OrderDomainMessageHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class OrderDomainMessageHandler implements MessageHandlerInterface, OrderDomainMessageHandlerInterface
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
