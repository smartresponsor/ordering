<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OutboxMessage;
use App\RepositoryInterface\Order\OutboxRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OutboxRepository implements OutboxRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $em) {}
    public function add(OutboxMessage $message): void { $this->em->persist($message); }
    public function pullPending(int $limit): iterable { return []; }
    public function markSent(OutboxMessage $message): void { $message->markSent(); }
    public function markFailed(OutboxMessage $message, int $delaySeconds = 0): void { $message->markFailed($delaySeconds); }
}
