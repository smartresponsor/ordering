<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Repository\Order\OrderReturnPolicyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderReturnPolicyRepository::class)]
#[ORM\Table(name: 'order_return_policy')]
class OrderReturnPolicyEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectSoftDeleteEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'code', type: 'string', length: 64, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(name: 'nameEntity', type: 'string', length: 255, nullable: true)]
    private ?string $nameEntity = null;

    #[ORM\Column(name: 'return_window_days', type: 'integer', options: ['default' => 0])]
    private int $returnWindowDays = 0;

    #[ORM\Column(name: 'description', type: 'text', nullable: true)]
    private ?string $description = null;

    public function __construct(?string $code = null)
    {
        $this->initializeObjectIdentity(objectSlug: $code);
        $this->initializeObjectAudit();
        $this->initializeObjectSoftDelete();
        $this->code = $code;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
