<?php

declare(strict_types=1);

namespace App\Entity\Outbox;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'outbox_messages')]
class OutboxMessage
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private string $id;

    #[ORM\Column(length: 64)]
    private string $aggregateId;

    #[ORM\Column(length: 128)]
    private string $eventType;

    #[ORM\Column(type: 'text')]
    private string $payload;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredAt;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $dispatched = false;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $dispatchedAt = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $attempts = 0;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $availableAt = null;

    /** @throws \JsonException */
    public function __construct(string $arg1, string|array $arg2, ?array $arg3 = null)
    {
        $this->id = Uuid::v7()->toRfc4122();
        $this->occurredAt = new \DateTimeImmutable();

        if (is_array($arg3)) {
            $this->aggregateId = $arg1;
            $this->eventType = (string) $arg2;
            $this->payload = json_encode($arg3, JSON_THROW_ON_ERROR);

            return;
        }

        $this->aggregateId = $this->id;
        $this->eventType = $arg1;
        $this->payload = json_encode(is_array($arg2) ? $arg2 : [], JSON_THROW_ON_ERROR);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function messageId(): string
    {
        return $this->id;
    }

    public function topic(): string
    {
        return $this->eventType;
    }

    public function getTopic(): string
    {
        return $this->eventType;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    /** @return array<string,mixed> */
    public function payload(): array
    {
        return $this->toArray()['payload'];
    }

    public function attempts(): int
    {
        return $this->attempts;
    }

    public function isDispatched(): bool
    {
        return $this->dispatched;
    }

    public function isPending(): bool
    {
        return !$this->dispatched
            && (null === $this->availableAt || $this->availableAt <= new \DateTimeImmutable());
    }

    public function markDispatched(): void
    {
        $this->dispatched = true;
        $this->dispatchedAt = new \DateTimeImmutable();
    }

    public function markSent(): void
    {
        $this->markDispatched();
    }

    public function markFailed(int $delaySeconds = 0): void
    {
        ++$this->attempts;
        $this->availableAt = new \DateTimeImmutable(sprintf('+%d seconds', max(0, $delaySeconds)));
    }

    /** @return array{id:string,aggregateId:string,eventType:string,payload:array<string,mixed>,occurredAt:string,dispatched:bool,dispatchedAt:?string,attempts:int,availableAt:?string} */
    public function toArray(): array
    {
        try {
            $payload = json_decode($this->payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            $payload = [];
        }

        return [
            'id' => $this->id,
            'aggregateId' => $this->aggregateId,
            'eventType' => $this->eventType,
            'payload' => is_array($payload) ? $payload : [],
            'occurredAt' => $this->occurredAt->format(DATE_ATOM),
            'dispatched' => $this->dispatched,
            'dispatchedAt' => $this->dispatchedAt?->format(DATE_ATOM),
            'attempts' => $this->attempts,
            'availableAt' => $this->availableAt?->format(DATE_ATOM),
        ];
    }
}
