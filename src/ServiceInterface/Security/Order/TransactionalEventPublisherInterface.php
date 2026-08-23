<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\ServiceInterface\Security\Order;

use App\Ordering\Entity\Order\OrderOutboxMessageEntity;

interface TransactionalEventPublisherInterface extends \App\Ordering\ServiceInterface\Messaging\Order\TransactionalEventPublisherInterface
{
    public function relay(OrderOutboxMessageEntity $m): void;
}
