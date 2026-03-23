<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order;

use App\EntityInterface\Order\OutboxMessageInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: App\Repository\Order\OutboxMessageRepository::class)]
#[ORM\Table(name: 'order_outbox_message')]
class OutboxMessage implements OutboxMessageInterface
{
    public const STATUS_PENDING = 'new';
    public const STATUS_RETRY = 'retry';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_DEAD = 'dead';

    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private ?Ulid $id = null;

    #[ORM\Column(type: 'string', length: 180)]
    private string $topic = '';

    #[ORM\Column(type: 'text')]
    private string $payload = '{}';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $header = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $availableAt;

    #[ORM\Column(type: 'smallint')]
    private int $attempt = 0;

    #[ORM\Column(type: 'string', length: 32)]
    private string $status = self::STATUS_PENDING;

    public function __construct(mixed ...$args)
    {
        $this->id ??= new Ulid();
        $now = new \DateTimeImmutable('now');
        $this->occurredAt = $now;
        $this->availableAt = $now;

        if ([] === $args) {
            return;
        }

        if (2 === count($args)) {
            [$topic, $payload] = $args;
            $this->topic = (string) $topic;
            $this->payload = $this->normalizePayload($payload);

            return;
        }

        if (count($args) >= 3) {
            [$aggregateId, $topic, $payload] = array_slice($args, 0, 3);
            $this->topic = (string) $topic;
            $this->payload = $this->normalizePayloadWithAggregate($payload, (string) $aggregateId);
        }
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function setTopic(string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(?string $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getOccurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function setOccurredAt(\DateTimeImmutable $at): self
    {
        $this->occurredAt = $at;

        return $this;
    }

    public function getAvailableAt(): \DateTimeImmutable
    {
        return $this->availableAt;
    }

    public function setAvailableAt(\DateTimeImmutable $at): self
    {
        $this->availableAt = $at;

        return $this;
    }

    public function getAttempt(): int
    {
        return $this->attempt;
    }

    public function setAttempt(int $attempt): self
    {
        $this->attempt = $attempt;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function messageId(): string
    {
        return $this->id?->toBase32() ?? '';
    }

    public function topic(): string
    {
        return $this->topic;
    }

    public function payload(): array
    {
        $decoded = json_decode($this->payload, true);

        return is_array($decoded) ? $decoded : ['payload' => $this->payload];
    }

    public function attempts(): int
    {
        return $this->attempt;
    }

    public function markSent(): void
    {
        $this->status = self::STATUS_SENT;
    }

    public function markFailed(?int $retryAfterSec = null): void
    {
        ++$this->attempt;

        if (null !== $retryAfterSec) {
            $this->status = self::STATUS_RETRY;
            $this->availableAt = new \DateTimeImmutable(sprintf('+%d seconds', max(0, $retryAfterSec)));

            return;
        }

        $this->status = self::STATUS_FAILED;
    }

    private function normalizePayload(mixed $payload): string
    {
        if (is_string($payload)) {
            return $payload;
        }

        return json_encode($payload, JSON_THROW_ON_ERROR);
    }

    private function normalizePayloadWithAggregate(mixed $payload, string $aggregateId): string
    {
        if (is_array($payload)) {
            if (!isset($payload['aggregateId']) && !isset($payload['messageId'])) {
                $payload['aggregateId'] = $aggregateId;
            }

            return json_encode($payload, JSON_THROW_ON_ERROR);
        }

        return $this->normalizePayload($payload);
    }
}
