<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Payment\Order;

use App\Model\Billing\Order\OrderPaymentIntent;
use App\Model\Billing\Order\OrderTransaction;
use App\ServiceInterface\Payment\Order\PaymentProcessorInterface;

final class OrderPaymentProcessor implements PaymentProcessorInterface
{
    public function createIntentId(): string
    {
        return 'pi_'.bin2hex(random_bytes(8));
    }

    public function capture(OrderPaymentIntent $intent): OrderTransaction
    {
        $txn = new OrderTransaction(
            $intent->getOrderId(),
            $intent->getAmount(),
            $intent->getCurrency(),
            'tx_'.bin2hex(random_bytes(8)),
        );
        $txn->confirm();

        return $txn;
    }
}
