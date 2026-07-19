<?php

declare(strict_types=1);

namespace App\Ordering\Entity\Order;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_audit_log')]
class OrderAuditLogEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'occurred_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredAt;

    #[ORM\Column(name: 'actor_type', length: 24)]
    private string $actorType;

    #[ORM\Column(name: 'actor_id', length: 64, nullable: true)]
    private ?string $actorId = null;

    #[ORM\Column(length: 64)]
    private string $action;

    #[ORM\Column(name: 'subject_type', length: 64, nullable: true)]
    private ?string $subjectType = null;

    #[ORM\Column(name: 'subject_id', length: 64, nullable: true)]
    private ?string $subjectId = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(name: 'user_agent', length: 255, nullable: true)]
    private ?string $userAgent = null;

    /** @var array<string, mixed>|null */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $payload = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $hash = null;

    /**
     * @param array<string, mixed>|null $payload
     */
    public function __construct(
        string $action,
        ?array $payload = null,
        ?string $subjectId = null,
        ?string $actorType = 'system',
        ?string $actorId = null,
        ?string $subjectType = 'order',
        ?string $ip = null,
        ?string $userAgent = null,
        ?string $hash = null,
        ?\DateTimeImmutable $occurredAt = null,
    ) {
        $this->action = $action;
        $this->payload = $payload;
        $this->subjectId = $subjectId;
        $this->actorType = $actorType ?? 'system';
        $this->actorId = $actorId;
        $this->subjectType = $subjectType;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->hash = $hash;
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function actorType(): string
    {
        return $this->actorType;
    }

    public function actorId(): ?string
    {
        return $this->actorId;
    }

    public function action(): string
    {
        return $this->action;
    }

    public function subjectType(): ?string
    {
        return $this->subjectType;
    }

    public function subjectId(): ?string
    {
        return $this->subjectId;
    }

    public function orderId(): ?string
    {
        return $this->subjectId;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function payload(): ?array
    {
        return $this->payload;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function ip(): ?string
    {
        return $this->ip;
    }

    public function userAgent(): ?string
    {
        return $this->userAgent;
    }

    public function hash(): ?string
    {
        return $this->hash;
    }
}
