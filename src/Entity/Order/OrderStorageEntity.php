<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Ordering\Repository\Order\OrderStorageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderStorageRepository::class)]
#[ORM\Table(name: 'order_storage')]
class OrderStorageEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectSoftDeleteEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'order_number', type: 'string', length: 100, unique: true, nullable: true)]
    private ?string $orderNumber = null;

    #[ORM\Column(name: 'vendor_id', type: 'string', length: 64, nullable: true)]
    private ?string $vendorId = null;

    #[ORM\Column(name: 'billing_address_id', type: 'string', length: 64, nullable: true)]
    private ?string $billingAddressId = null;

    #[ORM\Column(name: 'shipment_address_id', type: 'string', length: 64, nullable: true)]
    private ?string $shipmentAddressId = null;

    #[ORM\ManyToOne(targetEntity: OrderStatusEntity::class)]
    #[ORM\JoinColumn(name: 'order_status_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?OrderStatusEntity $status = null;

    #[ORM\Column(name: 'currency', type: 'string', length: 3)]
    private string $currency = 'USD';

    #[ORM\Column(name: 'subtotal', type: 'decimal', precision: 12, scale: 2, options: ['default' => '0.00'])]
    private string $subtotal = '0.00';

    #[ORM\Column(name: 'discount_total', type: 'decimal', precision: 12, scale: 2, options: ['default' => '0.00'])]
    private string $discountTotal = '0.00';

    #[ORM\Column(name: 'tax_total', type: 'decimal', precision: 12, scale: 2, options: ['default' => '0.00'])]
    private string $taxTotal = '0.00';

    #[ORM\Column(name: 'shipment_total', type: 'decimal', precision: 12, scale: 2, options: ['default' => '0.00'])]
    private string $shipmentTotal = '0.00';

    #[ORM\Column(name: 'total', type: 'decimal', precision: 12, scale: 2, options: ['default' => '0.00'])]
    private string $total = '0.00';

    #[ORM\Column(name: 'paid_total', type: 'decimal', precision: 12, scale: 2, options: ['default' => '0.00'])]
    private string $paidTotal = '0.00';

    /** @var Collection<int, OrderStatusHistoryEntity> */
    #[ORM\OneToMany(targetEntity: OrderStatusHistoryEntity::class, mappedBy: 'storageOrder', cascade: ['persist'], orphanRemoval: true)]
    private Collection $statusHistory;

    public function __construct(?string $orderNumber = null, string $currency = 'USD')
    {
        $this->initializeObjectIdentity(objectSlug: $orderNumber);
        $this->initializeObjectAudit();
        $this->initializeObjectSoftDelete();
        $this->orderNumber = $orderNumber;
        $this->currency = strtoupper($currency);
        $this->statusHistory = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function getVendorId(): ?string
    {
        return $this->vendorId;
    }

    public function getStatus(): ?OrderStatusEntity
    {
        return $this->status;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getTotal(): string
    {
        return $this->total;
    }
}
