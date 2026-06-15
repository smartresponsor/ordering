<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Repository\Order\OrderMetricsExportLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderMetricsExportLogRepository::class)]
#[ORM\Table(name: 'order_metrics_export_log')]
class OrderMetricsExportLogEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectSoftDeleteEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'export_key', type: 'string', length: 128, nullable: true)]
    private ?string $exportKey = null;

    #[ORM\Column(name: 'target', type: 'string', length: 128, nullable: true)]
    private ?string $target = null;

    #[ORM\Column(name: 'status', type: 'string', length: 64, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(name: 'payload', type: 'json', nullable: true)]
    private ?array $payload = null;

    public function __construct(?string $exportKey = null)
    {
        $this->initializeObjectIdentity(objectSlug: $exportKey);
        $this->initializeObjectAudit();
        $this->initializeObjectSoftDelete();
        $this->exportKey = $exportKey;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
