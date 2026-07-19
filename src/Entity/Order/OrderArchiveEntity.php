<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Ordering\Repository\Order\OrderArchiveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderArchiveRepository::class)]
#[ORM\Table(name: 'order_archive')]
class OrderArchiveEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectSoftDeleteEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'order_number', type: 'string', length: 100, nullable: true)]
    private ?string $orderNumber = null;

    #[ORM\Column(name: 'archived_reason', type: 'string', length: 255, nullable: true)]
    private ?string $archivedReason = null;

    #[ORM\Column(name: 'snapshot', type: 'json', nullable: true)]
    private ?array $snapshot = null;

    public function __construct(?string $orderNumber = null)
    {
        $this->initializeObjectIdentity(objectSlug: $orderNumber);
        $this->initializeObjectAudit();
        $this->initializeObjectSoftDelete();
        $this->orderNumber = $orderNumber;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
