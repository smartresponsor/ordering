<?php

declare(strict_types=1);

namespace App\Service\Subscriber\Order;

use App\Entity\Order;
use App\ServiceInterface\Subscriber\Order\OrderWorkflowGuardSubscriberInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Workflow\Event\GuardEvent;

final class OrderWorkflowGuardSubscriber implements OrderWorkflowGuardSubscriberInterface
{
    #[AsEventListener(event: 'workflow.order.guard.ship')]
    public function guardShip(GuardEvent $event): void
    {
        $subject = $event->getSubject();
        if ($subject instanceof Order) {
            if (bccomp($subject->paidTotal(), $subject->grandTotal(), 2) < 0) {
                $event->setBlocked(true, 'Order is not fully paid');
            }
        }
    }
}
