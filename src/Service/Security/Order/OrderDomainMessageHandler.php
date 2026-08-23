<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Security\Order;

use App\Ordering\Message\Domain\Order\OrderDomainMessage;
use App\Ordering\ServiceInterface\Security\Order\OrderDomainMessageHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderDomainMessageHandler implements OrderDomainMessageHandlerInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(OrderDomainMessage $msg): void
    {
        // Р’ СЂРµР°Р»СЊРЅРѕСЃС‚Рё: РѕС‚РїСЂР°РІРєР° РІРѕ РІРЅРµС€РЅРёРµ СЃРёСЃС‚РµРјС‹ / webhooks / analytics
        $this->logger->info('[OrderDomainMessage] consumed', [
            'topic' => $msg->topic,
            'messageId' => $msg->messageId,
        ]);
    }
}
