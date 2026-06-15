<?php

declare(strict_types=1);

namespace App\Subscriber\Workflow\Order;

use App\Entity\Order\OrderEntity;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Workflow\Event\GuardEvent;

final class OrderWorkflowGuardSubscriber
{
    #[AsEventListener(event: 'workflow.order.guard.ship')]
    public function guardShip(GuardEvent $event): void
    {
        $subject = $event->getSubject();
        if (!$subject instanceof OrderEntity) {
            return;
        }

        if (bccomp($subject->paidTotal(), $subject->grandTotal(), 2) < 0) {
            $event->setBlocked(true, 'Order is not fully paid');
        }
    }
}
