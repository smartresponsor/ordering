<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

final readonly class OrderPlaceCommand
{
    /** @param array{orderId:string,customerId:?string,vendorId:?string,currency:string,items:array<array{sku:string,qty:int,price:string}>,placeAt:string} $payload */
    public function __construct(public readonly array $payload) {
    }
}
