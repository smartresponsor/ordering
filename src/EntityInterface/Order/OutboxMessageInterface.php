<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\EntityInterface\Order;

use Symfony\Component\Uid\Ulid;

interface OutboxMessageInterface
{
    public function getId(): ?Ulid;

    public function setId(Ulid $id): self;

    public function getTopic(): string;

    public function setTopic(string $topic): self;

    public function getPayload(): string;

    public function setPayload(string $payload): self;

    public function getHeader(): ?string;

    public function setHeader(?string $header): self;

    public function getOccurredAt(): \DateTimeImmutable;

    public function setOccurredAt(\DateTimeImmutable $at): self;

    public function getAvailableAt(): \DateTimeImmutable;

    public function setAvailableAt(\DateTimeImmutable $at): self;

    public function getAttempt(): int;

    public function setAttempt(int $attempt): self;

    public function getStatus(): string;

    public function setStatus(string $status): self;
}
