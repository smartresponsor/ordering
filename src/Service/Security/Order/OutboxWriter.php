<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Outbox\OutboxMessage;
use App\ServiceInterface\Security\Order\OutboxWriterInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OutboxWriter implements OutboxWriterInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function store(string $topic, array $payload): void
    {
        $this->em->persist(new OutboxMessage($topic, $payload));
    }
}
