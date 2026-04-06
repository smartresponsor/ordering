<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\WebhookLog;
use Doctrine\ORM\EntityManagerInterface;

final class WebhookLogRepository
{
    /** @var array<string, WebhookLog> */
    private static array $logs = [];

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function exists(string $key): bool
    {
        return isset(self::$logs[$key]);
    }

    public function add(WebhookLog $log): void
    {
        self::$logs[$log->key()] = $log;
        $this->em->persist($log);
    }
}
