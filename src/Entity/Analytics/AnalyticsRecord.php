<?php

declare(strict_types=1);

namespace App\Entity\Analytics;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'analytics_records')]
class AnalyticsRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 64)]
    private string $type;

    #[ORM\Column(type: 'string', length: 64)]
    private string $orderId;

    public function __construct(string $type, string $orderId)
    {
        $this->id = null;
        $this->type = $type;
        $this->orderId = $orderId;
    }
}
