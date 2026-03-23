<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order;

use App\EntityInterface\Order\IdempotencyKeyInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\Order\IdempotencyKeyRepository::class)]
#[ORM\Table(name: 'order_idempotency_key')]
class IdempotencyKey implements IdempotencyKeyInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 128)]
    private string $key;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $owner = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $expireAt;

    public function __construct(string $key, ?string $owner, \DateTimeImmutable $expireAt)
    {
        $this->key = $key;
        $this->owner = $owner;
        $this->expireAt = $expireAt;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(?string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getExpireAt(): \DateTimeImmutable
    {
        return $this->expireAt;
    }

    public function setExpireAt(\DateTimeImmutable $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }
}
