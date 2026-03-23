<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\ApiResource\Order\RefundView;
use App\Repository\Order\OrderRefundTransactionRepository;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class ApiRefundController
{
    public function __construct(private OrderRefundTransactionRepository $repo)
    {
    }

    /** @return iterable<RefundView> */
    public function __invoke(string $id): iterable
    {
        $items = $this->repo->findBy(['orderId' => $id]);
        foreach ($items as $tx) {
            yield new RefundView($tx->id(), $tx->status());
        }
    }
}
