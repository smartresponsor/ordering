<?php

declare(strict_types=1);

namespace App\ServiceInterface\Security\Order;

use App\Entity\Order\Payment;

interface PaymentStateMachineInterface
{
    public function authorize(Payment $payment): void;

    public function capture(Payment $payment): void;

    public function settle(Payment $payment): void;

    public function void(Payment $payment): void;

    public function refund(Payment $payment): void;
}
