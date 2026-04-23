<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['order:read']], security: "is_granted('IS_AUTHENTICATED_FULLY')"),
        new Get(normalizationContext: ['groups' => ['order:read']], security: "is_granted('IS_AUTHENTICATED_FULLY')"),
        new Post(denormalizationContext: ['groups' => ['order:write']], security: "is_granted('IS_AUTHENTICATED_FULLY')"),
        new Patch(denormalizationContext: ['groups' => ['order:write']], security: "is_granted('IS_AUTHENTICATED_FULLY')"),
        new Delete(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['status' => 'exact'])]
readonly class OrderResource
{
    public function __construct(
        #[Groups(['order:read'])] public ?string $id = null,
        #[Groups(['order:read'])] public ?string $number = null,
        #[Groups(['order:read', 'order:write'])] public ?string $status = null,
        #[Groups(['order:read', 'order:write'])] public ?string $currency = null,
        #[Groups(['order:read'])] public ?string $total = null,
        #[Groups(['order:read'])] public ?string $grandTotal = null,
        #[Groups(['order:read'])] public ?string $paidTotal = null,
        #[Groups(['order:read'])] public ?string $refundedTotal = null,
        #[Groups(['order:read', 'order:write'])] public ?string $customerId = null,
        #[Groups(['order:read', 'order:write'])] public ?string $vendorId = null,
        /** @var list<object{sku:string,qty:int,price:string|int|float}>|null */
        #[Groups(['order:read', 'order:write'])] public ?array $items = null,
        #[Groups(['order:read', 'order:write'])] public ?string $placeAt = null,
    ) {
    }
}
