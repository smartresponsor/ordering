<?php

declare(strict_types=1);

namespace App\Service\Outbox;

use App\Entity\Outbox\IdempotencyKey;
use Doctrine\ORM\EntityManagerInterface;

final readonly class IdempotencyGuard
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function alreadyProcessed(string $key): bool
    {
        return (bool) $this->em->find(IdempotencyKey::class, $key);
    }

    public function remember(string $key): void
    {
        if (!$this->alreadyProcessed($key)) {
            $this->em->persist(new IdempotencyKey($key));
            $this->em->flush();
        }
    }
}
