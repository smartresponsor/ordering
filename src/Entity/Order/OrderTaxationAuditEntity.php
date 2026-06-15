<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Repository\Order\OrderTaxationAuditRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderTaxationAuditRepository::class)]
#[ORM\Table(name: 'order_taxation_audit')]
class OrderTaxationAuditEntity
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

    #[ORM\Column(name: 'tax_provider', type: 'string', length: 100, nullable: true)]
    private ?string $taxProvider = null;

    #[ORM\Column(name: 'tax_total', type: 'decimal', precision: 12, scale: 2, options: ['default' => '0.00'])]
    private string $taxTotal = '0.00';

    #[ORM\Column(name: 'payload', type: 'json', nullable: true)]
    private ?array $payload = null;

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
