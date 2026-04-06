<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OutboxMessage;
use App\RepositoryInterface\Order\OutboxRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OutboxRepository implements OutboxRepositoryInterface
{
    /** @var array<string, OutboxMessage> */
    private static array $messages = [];

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function add(OutboxMessage $message): void
    {
        self::$messages[$message->messageId()] = $message;
        $this->em->persist($message);
    }

    public function pullPending(int $limit): iterable
    {
        $pending = array_values(array_filter(
            self::$messages,
            static fn (OutboxMessage $message): bool => $message->isPending(),
        ));

        return array_slice($pending, 0, max(0, $limit));
    }

    public function markSent(OutboxMessage $message): void
    {
        $message->markSent();
        self::$messages[$message->messageId()] = $message;
    }

    public function markFailed(OutboxMessage $message, int $delaySeconds = 0): void
    {
        $message->markFailed($delaySeconds);
        self::$messages[$message->messageId()] = $message;
    }
}
