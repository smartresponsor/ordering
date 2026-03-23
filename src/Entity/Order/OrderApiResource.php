<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['order:read']]),
        new Get(normalizationContext: ['groups' => ['order:read']]),
        new Post(denormalizationContext: ['groups' => ['order:write']]),
        new Patch(denormalizationContext: ['groups' => ['order:status']]),
    ]
)]
class OrderApiResource
{
    public function __construct(
        #[Groups(['order:read'])] public ?string $id = null,
        #[Groups(['order:read', 'order:write'])] public ?string $status = null,
        #[Groups(['order:read', 'order:write'])] public ?string $currency = null,
        #[Groups(['order:read'])] public ?string $total = null,
    ) {
        if (null === $this->id) {
            $this->id = new Ulid();
        }
    }

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
