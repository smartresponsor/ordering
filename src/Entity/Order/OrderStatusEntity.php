<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectStateEmbeddableTrait;
use App\Repository\Order\OrderStatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderStatusRepository::class)]
#[ORM\Table(name: 'order_status')]
class OrderStatusEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectStateEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'order_status_code', type: 'string', length: 64, unique: true)]
    private string $code = '';

    #[ORM\Column(name: 'order_status_name', type: 'string', length: 255, nullable: true)]
    private ?string $nameEntity = null;

    #[ORM\Column(name: 'order_status_color', type: 'string', length: 64, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(name: 'order_status_description', type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'order_stock_handle', type: 'string', length: 16, options: ['default' => 'A'])]
    private string $stockHandle = 'A';

    #[ORM\Column(name: 'ordering', type: 'integer', options: ['default' => 0])]
    private int $ordering = 0;

    public function __construct(string $code = '', ?string $nameEntity = null)
    {
        $this->initializeObjectIdentity(objectSlug: $code ?: null);
        $this->initializeObjectAudit();
        $this->initializeObjectState(objectStatus: $code ?: 'draft');
        $this->code = $code;
        $this->nameEntity = $nameEntity;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): ?string
    {
        return $this->nameEntity;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStockHandle(): string
    {
        return $this->stockHandle;
    }

    public function getOrdering(): int
    {
        return $this->ordering;
    }
}
