<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\ServiceInterface\Order\PaymentServiceInterface;

final class PaymentService implements PaymentServiceInterface
{
    public function applyPayment(string $orderId, string $amount, string $txId): void
    {
        // Тут привязка к WriteModel + перерасчёт paid_total (опущено для краткости)
        // Событие отправляется через TransactionalEventPublisher в хендлере
    }
}
