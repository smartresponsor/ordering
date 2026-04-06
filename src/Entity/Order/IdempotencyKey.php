<?php

declare(strict_types=1);

namespace App\Entity\Order;

final class IdempotencyKey
{
    private string $scope;
    private string $keyHash;
    private \DateTimeImmutable $createdAt;

    public function __construct(string $scope, ?string $keyHash = null)
    {
        $this->scope = $scope;
        $this->keyHash = $keyHash ?? hash('sha256', $scope);
        $this->createdAt = new \DateTimeImmutable();
    }

    public function scope(): string { return $this->scope; }
    public function keyHash(): string { return $this->keyHash; }
}
