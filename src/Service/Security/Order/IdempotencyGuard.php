<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order\Billing\PaymentWebhookLog;
use App\ServiceInterface\Security\Order\IdempotencyGuardInterface;
use App\ServiceInterface\Security\Order\OrderIdempotencyGuardInterface;
use Doctrine\ORM\EntityManagerInterface;

final class IdempotencyGuard implements IdempotencyGuardInterface, OrderIdempotencyGuardInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function checkAndPersist(string $provider, string $eventId, string $payload): bool
    {
        $hash = hash('sha256', $payload);
        $repo = $this->em->getRepository(PaymentWebhookLog::class);
        $exists = $repo->findOneBy(['provider' => $provider, 'eventId' => $eventId]);
        if ($exists) {
            return false;
        }
        $existsHash = $repo->findOneBy(['payloadHash' => $hash]);
        if ($existsHash) {
            return false;
        }
        $log = new PaymentWebhookLog($provider, $eventId, $hash);
        $this->em->persist($log);
        $this->em->flush();

        return true;
    }
}
