<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectLocaleEmbeddableTrait;
use App\Repository\Order\OrderPaymentTranslationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderPaymentTranslationRepository::class)]
#[ORM\Table(name: 'order_payment_translation')]
#[ORM\UniqueConstraint(name: 'uniq_order_payment_translation_locale', columns: ['order_payment_id', 'object_locale'])]
class OrderPaymentTranslationEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectLocaleEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: OrderPaymentEntity::class)]
    #[ORM\JoinColumn(name: 'order_payment_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private OrderPaymentEntity $payment;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public function __construct(OrderPaymentEntity $payment, string $locale = 'en_US')
    {
        $this->initializeObjectIdentity();
        $this->initializeObjectAudit();
        $this->initializeObjectLocale($locale);
        $this->payment = $payment;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPayment(): OrderPaymentEntity
    {
        return $this->payment;
    }
}
