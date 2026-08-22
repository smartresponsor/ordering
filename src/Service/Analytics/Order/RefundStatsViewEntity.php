<?php

declare(strict_types=1);

namespace App\Ordering\Service\Analytics\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'refund_stats_view')]
#[ORM\Index(name: 'idx_refund_stats_date', columns: ['date'])]
class RefundStatsViewEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $date;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $refundCount = 0;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    private string $refundTotal = '0.00';

    public function __construct(\DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function addRefund(string $amount): void
    {
        ++$this->refundCount;
        $this->refundTotal = bcadd($this->refundTotal, $amount, 2);
    }
}
