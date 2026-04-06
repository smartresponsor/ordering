<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\IdempotencyKey;
use Doctrine\ORM\EntityManagerInterface;

final class IdempotencyKeyRepository
{
    /** @var array<string, IdempotencyKey> */
    private static array $keys = [];

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function exists(string $key): bool
    {
        $hash = hash('sha256', $key);

        if (isset(self::$keys[$hash])) {
            return true;
        }

        return null !== $this->em->getRepository(IdempotencyKey::class)->findOneBy(['keyHash' => $hash]);
    }

    public function save(IdempotencyKey $key): void
    {
        self::$keys[$key->keyHash()] = $key;
        $this->em->persist($key);
    }
}
