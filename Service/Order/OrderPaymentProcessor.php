<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order\Billing\OrderPaymentIntent;
use App\Entity\Order\Billing\OrderTransaction;
use App\ServiceInterface\Order\PaymentProcessorInterface;

final class OrderPaymentProcessor implements PaymentProcessorInterface
{
    public function createIntentId(): string
    {
        return 'pi_'.bin2hex(random_bytes(8));
    }

    public function capture(OrderPaymentIntent $intent): OrderTransaction
    {
        $txn = new OrderTransaction($intent->getOrder(), 'tx_'.bin2hex(random_bytes(8)), $intent->getAmount(), 'USD');
        $txn->confirm();

        return $txn;
    }
}
