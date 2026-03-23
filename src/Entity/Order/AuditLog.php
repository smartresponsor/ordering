<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: 'App\\Repository\\Order\\AuditLogRepository')]
#[ORM\Table(name: 'order_audit_log')]
class AuditLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $occurredAt;

    #[ORM\Column(length: 24)]
    private string $actorType; // system|user|webhook

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $actorId = null;

    #[ORM\Column(length: 64)]
    private string $action; // placed|paid|refunded|webhook.accepted|webhook.rejected...

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $subjectType = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $subjectId = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $payload = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $hash = null;

    public function __construct(string $actorType, string $action)
    {
        if (null === $this->id) {
            $this->id = new Ulid();
        }

        $this->actorType = $actorType;
        $this->action = $action;
        $this->occurredAt = new \DateTimeImmutable('now');
    }

    // getters/setters omitted for brevity
    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function setId(Ulid $id): self
    {
        $this->id = $id;

        return $this;
    }
}
