<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\EntityInterface\Order;

interface OrderInvoiceInterface
{
    public function __construct(Order $order, InvoiceNumber $invoiceNumber, string $amountTotal, string $amountTax);

    public function getId(): ?int;

    public function getOrder(): Order;

    public function getInvoiceNumber(): InvoiceNumber;

    public function getAmountTotal(): string;

    public function getAmountTax(): string;

    public function getIssuedAt(): \DateTimeImmutable;
}
