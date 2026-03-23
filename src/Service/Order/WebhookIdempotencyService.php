<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\WebhookLog;
use App\Repository\Order\WebhookLogRepository;
use Doctrine\ORM\EntityManagerInterface;

final class WebhookIdempotencyService
{
    public function __construct(
        private readonly WebhookLogRepository $logs,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function handleOnce(string $key, string $eventType, string $payload, callable $callback): bool
    {
        if ($this->logs->exists($key)) {
            return false; // duplicate webhook
        }
        $this->logs->add(new WebhookLog($key, $eventType, $payload));
        $callback();
        $this->em->flush();

        return true;
    }
}
