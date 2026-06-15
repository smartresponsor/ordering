<?php

declare(strict_types=1);

namespace App\Subscriber\Event\Order;

use App\Model\Order\OrderPriceAudit;
use App\ServiceInterface\Pricing\Order\OrderPricingInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Uid\Uuid;

final readonly class OrderWorkflowSubscriber
{
    public function __construct(
        private OrderPricingInterface $pricing,
        private EntityManagerInterface $em,
    ) {
    }

    #[AsEventListener(event: 'order.placed')]
    public function onOrderPlaced(object $event): void
    {
        $orderId = is_scalar($event->orderId ?? null) ? (string) $event->orderId : 'unknown';
        $lines = $event->lines ?? [50.0, 50.0];
        $base = $this->sumLines($lines);
        $total = $this->pricing->price($base, 0.2);

        $audit = new OrderPriceAudit(
            $orderId,
            'order.placed.snapshot',
            [
                'auditId' => Uuid::v7()->toRfc4122(),
                'base' => $base,
                'total' => $total,
                'lineCount' => count($lines),
                'currency' => 'USD',
            ],
        );

        $this->em->persist($audit);
        $this->em->flush();
    }

    private function sumLines(mixed $lines): float
    {
        if (!is_iterable($lines)) {
            return 0.0;
        }

        $total = 0.0;
        foreach ($lines as $line) {
            if (is_numeric($line)) {
                $total += (float) $line;
            }
        }

        return $total;
    }
}
