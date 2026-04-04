<?php

declare(strict_types=1);

namespace App\Service\Subscriber\Order;

use App\Entity\Order\OrderPriceAudit;
use App\ValueObject\Pricing\Order\Currency;
use App\ValueObject\Pricing\Order\Discount;
use App\ValueObject\Pricing\Order\TaxRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class OrderWorkflowSubscriber
{
    public function __construct(
        private \App\ServiceInterface\Pricing\Order\OrderPricingServiceInterface $pricing,
        private EntityManagerInterface $em,
    ) {
    }

    #[AsEventListener(event: 'order.placed')]
    public function onOrderPlaced(object $event): void
    {
        $orderId = $event->orderId ?? 'unknown';
        $lines = $event->lines ?? [5000, 5000];
        $currency = new Currency('USD');
        $discount = Discount::percent('10');
        $tax = new TaxRate('20');

        $detail = $this->pricing->calculate($lines, $currency, $discount, $tax);
        $this->em->persist($detail);

        $audit = new OrderPriceAudit(
            \Ramsey\Uuid\Uuid::uuid4()->toString(),
            $orderId,
            $detail->currency()->code(),
            $detail->subtotalMinor(),
            $detail->discountMinor(),
            $detail->taxMinor(),
            $detail->totalMinor(),
            'order.placed.snapshot'
        );
        $this->em->persist($audit);
        $this->em->flush();
    }
}
