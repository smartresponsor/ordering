<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Security\Order;

use App\Ordering\ServiceInterface\Security\Order\IdempotencyGuardInterface;
use App\Ordering\ServiceInterface\Security\Order\OrderIdempotencyGuardInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class IdempotencyGuard implements IdempotencyGuardInterface, OrderIdempotencyGuardInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function checkAndPersist(string $provider, string $eventId, string $payload): bool
    {
        $compositeKey = $provider.'|'.$eventId.'|'.hash('sha256', $payload);
        $repo = $this->em->getRepository(WebhookLog::class);
        $exists = $repo->findOneBy(['key' => $compositeKey]);
        if (null !== $exists) {
            return false;
        }

        $log = new WebhookLog($compositeKey, 'payment_webhook', $payload);
        $this->em->persist($log);
        $this->em->flush();

        return true;
    }
}
