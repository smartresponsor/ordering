<?php

declare(strict_types=1);

namespace App\Service\Outbox;

use App\Entity\Outbox\OutboxMessage;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OutboxWriter
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @throws \JsonException
     */
    public function store(string $topic, array $payload): void
    {
        $aggregateId = (string) ($payload['orderId'] ?? $payload['aggregateId'] ?? $payload['id'] ?? $topic);
        $this->em->persist(new OutboxMessage($aggregateId, $topic, $payload));
    }
}
