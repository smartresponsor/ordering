<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

interface MoneyInterface
{
    public function getAmount(): string;

    public function getCurrency(): Currency;

    public function add(self $other): self;

    public function subtract(self $other): self;

    public function multiply(string $factor): self;

    public function round(int $scale = 2): self;

    public function equals(self $other): bool;

    public function __toString(): string;
}
