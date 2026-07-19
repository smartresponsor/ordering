<?php

declare(strict_types=1);

namespace App\ServiceInterface\Payment\Order;

use App\Ordering\Entity\Order\OrderPaymentEntity;

interface PaymentStateMachineInterface
{
    public function authorize(OrderPaymentEntity $payment): void;

    public function capture(OrderPaymentEntity $payment): void;

    public function settle(OrderPaymentEntity $payment): void;

    public function void(OrderPaymentEntity $payment): void;

    public function refund(OrderPaymentEntity $payment): void;
}
