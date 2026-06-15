<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ApiResource\View\Order\OrderResource;
use App\Entity\Order\OrderEntity;
use App\ServiceInterface\Security\Order\OrderEntityToResourceTransformerInterface;

final class OrderEntityToResourceTransformer implements OrderEntityToResourceTransformerInterface
{
    public function transform(OrderEntity $e): OrderResource
    {
        return new OrderResource(
            id: $e->slug(),
            status: $e->getStatus(),
            currency: method_exists($e, 'getCurrency') ? $e->getCurrency() : null,
            total: method_exists($e, 'getTotal') ? $e->getTotal() : null,
        );
    }
}
