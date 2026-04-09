<?php

declare(strict_types=1);

namespace App\ApiResource\View\Order;

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
        new GetCollection(security: "is_granted('IS_AUTHENTICATED_FULLY')", normalizationContext: ['groups' => ['order:read']]),
        new Get(security: "is_granted('IS_AUTHENTICATED_FULLY')", normalizationContext: ['groups' => ['order:read']]),
        new Post(security: "is_granted('IS_AUTHENTICATED_FULLY')", denormalizationContext: ['groups' => ['order:write']]),
        new Patch(security: "is_granted('IS_AUTHENTICATED_FULLY')", denormalizationContext: ['groups' => ['order:write']]),
        new Delete(security: "is_granted('IS_AUTHENTICATED_FULLY')"),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['status' => 'exact'])]
final readonly class OrderResource
{
    public function __construct(
        #[Groups(['order:read'])] public readonly ?string $id = null,
        #[Groups(['order:read'])] public readonly ?string $number = null,
        #[Groups(['order:read', 'order:write'])] public readonly ?string $status = null,
        #[Groups(['order:read', 'order:write'])] public readonly ?string $currency = null,
        #[Groups(['order:read'])] public readonly ?string $total = null,
        #[Groups(['order:read'])] public readonly ?string $grandTotal = null,
        #[Groups(['order:read'])] public readonly ?string $paidTotal = null,
        #[Groups(['order:read'])] public readonly ?string $refundedTotal = null,
        #[Groups(['order:read', 'order:write'])] public readonly ?string $customerId = null,
        #[Groups(['order:read', 'order:write'])] public readonly ?string $vendorId = null,
        /** @var list<object{sku:string,qty:int,price:string|int|float}>|null */
        #[Groups(['order:read', 'order:write'])] public readonly ?array $items = null,
        #[Groups(['order:read', 'order:write'])] public readonly ?string $placeAt = null,
    ) {
    }
}
