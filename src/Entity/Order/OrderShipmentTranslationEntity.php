<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectLocaleEmbeddableTrait;
use App\Repository\Order\OrderShipmentTranslationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderShipmentTranslationRepository::class)]
#[ORM\Table(name: 'order_shipment_translation')]
#[ORM\UniqueConstraint(name: 'uniq_order_shipment_translation_locale', columns: ['order_shipment_id', 'object_locale'])]
class OrderShipmentTranslationEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectLocaleEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderShipmentEntity::class)]
    #[ORM\JoinColumn(name: 'order_shipment_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private OrderShipmentEntity $shipment;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public function __construct(OrderShipmentEntity $shipment, string $locale = 'en_US')
    {
        $this->initializeObjectIdentity();
        $this->initializeObjectAudit();
        $this->initializeObjectLocale($locale);
        $this->shipment = $shipment;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShipment(): OrderShipmentEntity
    {
        return $this->shipment;
    }
}
