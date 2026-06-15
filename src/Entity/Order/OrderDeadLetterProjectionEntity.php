<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Objecting\EntityTrait\Embeddable\ObjectAuditEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectIdentityEmbeddableTrait;
use App\Objecting\EntityTrait\Embeddable\ObjectSoftDeleteEmbeddableTrait;
use App\Repository\Order\OrderDeadLetterProjectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDeadLetterProjectionRepository::class)]
#[ORM\Table(name: 'order_dead_letter_projection')]
class OrderDeadLetterProjectionEntity
{
    use ObjectIdentityEmbeddableTrait;
    use ObjectAuditEmbeddableTrait;
    use ObjectSoftDeleteEmbeddableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'message_id', type: 'string', length: 128, nullable: true)]
    private ?string $messageId = null;

    #[ORM\Column(name: 'message_class', type: 'string', length: 255, nullable: true)]
    private ?string $messageClass = null;

    #[ORM\Column(name: 'reason', type: 'text', nullable: true)]
    private ?string $reason = null;

    #[ORM\Column(name: 'payload', type: 'json', nullable: true)]
    private ?array $payload = null;

    public function __construct(?string $messageId = null)
    {
        $this->initializeObjectIdentity(objectSlug: $messageId);
        $this->initializeObjectAudit();
        $this->initializeObjectSoftDelete();
        $this->messageId = $messageId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
