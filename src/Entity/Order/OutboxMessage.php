<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Symfony\Component\Uid\Uuid;

final class OutboxMessage
{
    private string $messageId;
    private string $topic;
    private array $payload;
    private int $attempts = 0;
    private bool $sent = false;
    private ?\DateTimeImmutable $availableAt = null;

    public function __construct(string $arg1, string|array $arg2, ?array $arg3 = null)
    {
        if (is_array($arg3)) {
            $this->messageId = $arg1;
            $this->topic = (string) $arg2;
            $this->payload = $arg3;

            return;
        }

        $this->messageId = Uuid::v7()->toRfc4122();
        $this->topic = $arg1;
        $this->payload = is_array($arg2)
            ? $arg2
            : (array) json_decode($arg2, true, 512, JSON_THROW_ON_ERROR);
    }

    public function messageId(): string
    {
        return $this->messageId;
    }

    public function topic(): string
    {
        return $this->topic;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function attempts(): int
    {
        return $this->attempts;
    }

    public function markSent(): void
    {
        $this->sent = true;
    }

    public function markFailed(int $delaySeconds = 0): void
    {
        ++$this->attempts;
        $this->availableAt = new \DateTimeImmutable()->modify(
            sprintf('+%d seconds', max(0, $delaySeconds)),
        );
    }

    public function isPending(): bool
    {
        return !$this->sent
            && (null === $this->availableAt || $this->availableAt <= new \DateTimeImmutable());
    }
}
