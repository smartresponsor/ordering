<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ApiResource\View\Order\OrderResource;
use App\Entity\Order\Order as OrderEntity;
use App\ServiceInterface\Security\Order\OrderEntityToResourceTransformerInterface;

final class OrderEntityToResourceTransformer implements OrderEntityToResourceTransformerInterface
{
    public function transform(OrderEntity $e): OrderResource
    {
        return new OrderResource(
            id: $e->getId(),
            status: $e->getStatus(),
            currency: method_exists($e, 'getCurrency') ? (string) $e->getCurrency() : null,
            total: method_exists($e, 'getTotal') ? (string) $e->getTotal() : null,
        );
    }
}
