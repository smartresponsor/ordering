<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Entity\Order\Entity\Payment;

interface PaymentInterface
{
    public function id(): string;

    public function status(): PaymentStatus;

    public function setStatus(PaymentStatus $s): void;
}
