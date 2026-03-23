<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\Entity\Order\WebhookLog;
use App\RepositoryInterface\Order\WebhookLogRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class WebhookLogRepository implements WebhookLogRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function exists(string $key): bool
    {
        return (bool) $this->em->getRepository(WebhookLog::class)->findOneBy(['idempotencyKey' => $key]);
    }

    public function add(WebhookLog $log): void
    {
        $this->em->persist($log);
    }
}
