<?php

declare(strict_types=1);

namespace App\Service\Analytics\Order;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Api\Provider\OrderMetricsProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrderMetricsViewRepository::class)]
#[ORM\Table(name: 'order_metrics_view')]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/vendors/{vendorId}/metrics',
            normalizationContext: ['groups' => ['metrics:read']],
            provider: OrderMetricsProvider::class
        ),
    ]
)]
class OrderMetricsView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['metrics:read'])]
    public ?int $id = null;

    #[ORM\Column(length: 36)]
    #[Groups(['metrics:read'])]
    public string $vendorId;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['metrics:read'])]
    public int $totalOrders = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['metrics:read'])]
    public int $completedOrders = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['metrics:read'])]
    public int $cancelledOrders = 0;

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    #[Groups(['metrics:read'])]
    public string $totalRevenue = '0.00';

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    #[Groups(['metrics:read'])]
    public string $refundedAmount = '0.00';

    #[ORM\Column(type: 'decimal', precision: 20, scale: 2, options: ['default' => '0.00'])]
    #[Groups(['metrics:read'])]
    public string $avgOrderValue = '0.00';

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['metrics:read'])]
    public \DateTimeImmutable $updatedAt;

    public function __construct(string $vendorId)
    {
        $this->vendorId = $vendorId;
        $this->updatedAt = new \DateTimeImmutable('now');
    }
}
