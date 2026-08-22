<?php

declare(strict_types=1);

namespace App\Ordering\Service\State\Order;

use App\Model\Order\Delivery;
use App\Ordering\ServiceInterface\State\Order\DeliveryStateMachineInterface;

final class DeliveryStateMachine implements DeliveryStateMachineInterface
{
    public function ship(Delivery $delivery): void
    {
        $this->transit($delivery, 'shipped');
    }

    public function deliver(Delivery $delivery): void
    {
        $this->transit($delivery, 'delivered');
    }

    public function markLost(Delivery $delivery): void
    {
        $this->transit($delivery, 'lost');
    }

    public function return(Delivery $delivery): void
    {
        $this->transit($delivery, 'returned');
    }

    private function transit(Delivery $delivery, string $targetStatus): void
    {
        $from = $delivery->status();
        $allowed = match ($from) {
            'ready' => ['shipped'],
            'shipped' => ['delivered', 'lost', 'returned'],
            'delivered' => ['returned'],
            'lost', 'returned' => [],
            default => throw new \InvalidArgumentException(sprintf('Unknown delivery status "%s".', $from)),
        };

        if ($from === $targetStatus) {
            return;
        }

        if (!in_array($targetStatus, $allowed, true)) {
            throw new \InvalidArgumentException(sprintf('Transition from %s to %s is not allowed.', $from, $targetStatus));
        }

        $delivery->setStatus($targetStatus);
    }
}
