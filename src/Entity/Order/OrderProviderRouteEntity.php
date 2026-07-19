<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Ordering\Repository\Order\OrderProviderRouteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderProviderRouteRepository::class)]
#[ORM\Table(name: 'order_provider_route')]
class OrderProviderRouteEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectSoftDeleteEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'provider_key', type: 'string', length: 128, nullable: true)]
    private ?string $providerKey = null;

    #[ORM\Column(name: 'route_key', type: 'string', length: 128, nullable: true)]
    private ?string $routeKey = null;

    #[ORM\Column(name: 'enabled', type: 'boolean', options: ['default' => false])]
    private bool $enabled = false;

    #[ORM\Column(name: 'metadata', type: 'json', nullable: true)]
    private ?array $metadata = null;

    public function __construct(?string $providerKey = null)
    {
        $this->initializeObjectIdentity(objectSlug: $providerKey);
        $this->initializeObjectAudit();
        $this->initializeObjectSoftDelete();
        $this->providerKey = $providerKey;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
