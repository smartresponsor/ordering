<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Repository\Order;

use App\RepositoryInterface\Order\OrderPriceDetailRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class OrderPriceDetailRepository extends EntityRepository implements OrderPriceDetailRepositoryInterface
{
}
