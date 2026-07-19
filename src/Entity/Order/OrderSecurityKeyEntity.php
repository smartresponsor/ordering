<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Ordering\Repository\Order\OrderSecurityKeyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderSecurityKeyRepository::class)]
#[ORM\Table(name: 'order_security_key')]
class OrderSecurityKeyEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectSoftDeleteEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'key_id', type: 'string', length: 128, nullable: true)]
    private ?string $keyId = null;

    #[ORM\Column(name: 'algorithm', type: 'string', length: 64, nullable: true)]
    private ?string $algorithm = null;

    #[ORM\Column(name: 'public_key', type: 'text', nullable: true)]
    private ?string $publicKey = null;

    #[ORM\Column(name: 'metadata', type: 'json', nullable: true)]
    private ?array $metadata = null;

    public function __construct(?string $keyId = null)
    {
        $this->initializeObjectIdentity(objectSlug: $keyId);
        $this->initializeObjectAudit();
        $this->initializeObjectSoftDelete();
        $this->keyId = $keyId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
