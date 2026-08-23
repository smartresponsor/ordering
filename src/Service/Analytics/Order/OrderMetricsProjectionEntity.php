<?php

declare(strict_types=1);

namespace App\Ordering\Service\Analytics\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_metrics_projection')]
#[ORM\Index(name: 'idx_omp_date', columns: ['date'])]
class OrderMetricsProjectionEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'date_immutable')]
    private \DateTimeImmutable $date;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $ordersCount = 0;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    private string $grossTotal = '0.00';

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    private string $refundTotal = '0.00';

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    private string $netTotal = '0.00';

    public function __construct(\DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function addOrder(string $amount): void
    {
        ++$this->ordersCount;
        $this->grossTotal = bcadd($this->grossTotal, $amount, 2);
        $this->recompute();
    }

    public function addRefund(string $amount): void
    {
        $this->refundTotal = bcadd($this->refundTotal, $amount, 2);
        $this->recompute();
    }

    private function recompute(): void
    {
        $this->netTotal = bcsub($this->grossTotal, $this->refundTotal, 2);
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getOrdersCount(): int
    {
        return $this->ordersCount;
    }

    public function getGrossTotal(): string
    {
        return $this->grossTotal;
    }

    public function getRefundTotal(): string
    {
        return $this->refundTotal;
    }

    public function getNetTotal(): string
    {
        return $this->netTotal;
    }
}
