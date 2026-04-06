<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\IdempotencyKey;
use Doctrine\ORM\EntityManagerInterface;

final class IdempotencyKeyRepository
{
    public function __construct(private readonly EntityManagerInterface $em) {}
    public function exists(string $key): bool { return false; }
    public function save(IdempotencyKey $key): void { $this->em->persist($key); }
}
