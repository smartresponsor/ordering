<?php

declare(strict_types=1);

namespace App\Ordering\Service\Analytics\Order;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Ordering\Provider\Api\OrderMetricsAggregateProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrderMetricsAggregateViewRepository::class)]
#[ORM\Table(name: 'order_metrics_aggregate_view')]
#[ORM\UniqueConstraint(name: 'uniq_vendor_period', columns: ['vendor_id', 'period_type', 'period_value'])]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/vendors/{vendorId}/metrics/aggregate',
            normalizationContext: ['groups' => ['metricsAgg:read']],
            provider: OrderMetricsAggregateProvider::class
        ),
    ]
)]
class OrderMetricsAggregateView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['metricsAgg:read'])]
    public ?int $id = null;

    #[ORM\Column(name: 'vendor_id', length: 36)]
    #[Groups(['metricsAgg:read'])]
    public string $vendorId;

    #[ORM\Column(name: 'period_type', length: 8)]
    #[Groups(['metricsAgg:read'])]
    public string $periodType; // day | week | month

    #[ORM\Column(name: 'period_value', length: 16)]
    #[Groups(['metricsAgg:read'])]
    public string $periodValue; // e.g. 2025-10-07 / 2025-W41 / 2025-10

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['metricsAgg:read'])]
    public int $totalOrders = 0;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    #[Groups(['metricsAgg:read'])]
    public string $totalRevenue = '0.00';

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    #[Groups(['metricsAgg:read'])]
    public string $refundedAmount = '0.00';

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    #[Groups(['metricsAgg:read'])]
    public string $ltv = '0.00'; // totalRevenue / totalOrders

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['metricsAgg:read'])]
    public \DateTimeImmutable $updatedAt;

    public function __construct(string $vendorId, string $periodType, string $periodValue)
    {
        $this->vendorId = $vendorId;
        $this->periodType = $periodType;
        $this->periodValue = $periodValue;
        $this->updatedAt = new \DateTimeImmutable('now');
    }

    public function recomputeLtv(): void
    {
        $orders = max(1, $this->totalOrders);
        $this->ltv = bcdiv($this->totalRevenue, (string) $orders, 2);
    }
}
