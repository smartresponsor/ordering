<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\ApiResource\View\Order\OrderResource;
use App\Entity\Order as OrderEntity;
use App\ServiceInterface\Security\Order\OrderResourceToEntityTransformerInterface;

final class OrderResourceToEntityTransformer implements OrderResourceToEntityTransformerInterface
{
    public function transform(OrderResource $r): OrderEntity
    {
        $e = new OrderEntity($r->id ?? uuid_create(UUID_TYPE_RANDOM));
        if ($r->status) {
            $e->setStatus($r->status);
        }

        return $e;
    }
}
