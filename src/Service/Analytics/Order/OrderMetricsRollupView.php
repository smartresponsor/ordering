<?php

declare(strict_types=1);

namespace App\Service\Analytics\Order;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Api\Provider\OrderMetricsRollupProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrderMetricsRollupViewRepository::class)]
#[ORM\Table(name: 'order_metrics_rollup_view')]
#[ORM\UniqueConstraint(name: 'uniq_vendor_period_roll', columns: ['vendor_id', 'period_type', 'period_value'])]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/vendors/{vendorId}/metrics/rollup',
            provider: OrderMetricsRollupProvider::class,
            normalizationContext: ['groups' => ['metricsRoll:read']]
        ),
    ]
)]
class OrderMetricsRollupView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['metricsRoll:read'])]
    public ?int $id = null;

    #[ORM\Column(name: 'vendor_id', length: 36)]
    #[Groups(['metricsRoll:read'])]
    public string $vendorId;

    #[ORM\Column(name: 'period_type', length: 8)]
    #[Groups(['metricsRoll:read'])]
    public string $periodType; // quarter | year

    #[ORM\Column(name: 'period_value', length: 8)]
    #[Groups(['metricsRoll:read'])]
    public string $periodValue; // e.g. 2025-Q4 | 2025

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['metricsRoll:read'])]
    public int $totalOrders = 0;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    #[Groups(['metricsRoll:read'])]
    public string $totalRevenue = '0.00';

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    #[Groups(['metricsRoll:read'])]
    public string $refundedAmount = '0.00';

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    #[Groups(['metricsRoll:read'])]
    public string $ltv = '0.00';

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['metricsRoll:read'])]
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
