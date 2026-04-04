<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Webhook\Order;

use App\Repository\Order\WebhookLogRepository;
use Doctrine\ORM\EntityManagerInterface;

interface WebhookIdempotencyServiceInterface
{
    public function __construct(
        WebhookLogRepository $logs,
        EntityManagerInterface $em,
    );

    public function handleOnce(string $key, string $eventType, string $payload, callable $callback): bool;
}
