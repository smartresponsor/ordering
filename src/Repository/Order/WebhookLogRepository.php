<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\WebhookLog;
use Doctrine\ORM\EntityManagerInterface;

final class WebhookLogRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function exists(string $key): bool
    {
        return null !== $this->em->find(WebhookLog::class, $key);
    }

    public function add(WebhookLog $log): void
    {
        $this->em->persist($log);
    }
}
