<?php

declare(strict_types=1);

namespace App\Service\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'vendor_revenue_view')]
#[ORM\Index(columns: ['vendor_id', 'date'], name: 'idx_vendor_rev_vendor_date')]
class VendorRevenueView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'vendor_id', length: 64)]
    private string $vendorId;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $date;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $ordersCount = 0;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    private string $grossTotal = '0.00';

    public function __construct(string $vendorId, \DateTimeImmutable $date)
    {
        $this->vendorId = $vendorId;
        $this->date = $date;
    }

    public function addOrder(string $amount): void
    {
        ++$this->ordersCount;
        $this->grossTotal = bcadd($this->grossTotal, $amount, 2);
    }
}
