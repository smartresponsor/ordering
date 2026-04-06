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
final class OrderResource
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
