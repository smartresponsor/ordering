<?php

declare(strict_types=1);

namespace App\Entity\Outbox;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'idempotency_key')]
class IdempotencyKey
{
    #[ORM\Id]
    #[ORM\Column(length: 128)]
    private string $key;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $scope = null;

    #[ORM\Column(length: 64, unique: true)]
    private string $keyHash;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $scopeOrKey, ?string $keyHash = null)
    {
        $this->scope = $scopeOrKey;
        $this->key = null === $keyHash ? $scopeOrKey : $keyHash;
        $this->keyHash = $keyHash ?? hash('sha256', $scopeOrKey);
        $this->createdAt = new \DateTimeImmutable();
    }

    public function key(): string
    {
        return $this->key;
    }

    public function scope(): string
    {
        return $this->scope ?? $this->key;
    }

    public function keyHash(): string
    {
        return $this->keyHash;
    }
}
