<?php

declare(strict_types=1);

namespace App\Ordering\Service\Analytics\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_metrics')]
class OrderMetricsEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'string', length: 64)]
    private string $vendorId;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $day;

    #[ORM\Column(type: 'bigint')]
    private string $ordersCount;

    #[ORM\Column(type: 'bigint')]
    private string $paidMinor;

    #[ORM\Column(type: 'bigint')]
    private string $refundedMinor;

    public function __construct(string $id, string $vendorId, \DateTimeImmutable $day, int $ordersCount, int $paidMinor, int $refundedMinor)
    {
        $this->id = $id;
        $this->vendorId = $vendorId;
        $this->day = $day;
        $this->ordersCount = (string) $ordersCount;
        $this->paidMinor = (string) $paidMinor;
        $this->refundedMinor = (string) $refundedMinor;
    }
}
