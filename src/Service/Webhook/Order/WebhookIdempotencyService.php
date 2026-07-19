<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Webhook\Order;

use App\Ordering\Repository\Order\WebhookLogRepository;
use App\ServiceInterface\Webhook\Order\WebhookIdempotencyServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class WebhookIdempotencyService implements WebhookIdempotencyServiceInterface
{
    public function __construct(
        private WebhookLogRepository $logs,
        private EntityManagerInterface $em,
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
