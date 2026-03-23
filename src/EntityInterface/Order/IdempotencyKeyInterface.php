<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\EntityInterface\Order;

interface IdempotencyKeyInterface
{
    public function getKey(): string;

    public function setKey(string $key): self;

    public function getOwner(): ?string;

    public function setOwner(?string $owner): self;

    public function getExpireAt(): \DateTimeImmutable;

    public function setExpireAt(\DateTimeImmutable $expireAt): self;
}
