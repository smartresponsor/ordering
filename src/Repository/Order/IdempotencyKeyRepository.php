<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use Doctrine\ORM\EntityManagerInterface;

final class IdempotencyKeyRepository
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function exists(string $key): bool
    {
        $hash = hash('sha256', $key);

        return null !== $this->em->getRepository(IdempotencyKey::class)
            ->findOneBy(['keyHash' => $hash]);
    }

    public function save(IdempotencyKey $key): void
    {
        $this->em->persist($key);
    }
}
