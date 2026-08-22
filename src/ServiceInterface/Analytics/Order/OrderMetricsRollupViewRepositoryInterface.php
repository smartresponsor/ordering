<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\Ordering\ServiceInterface\Analytics\Order;

interface OrderMetricsRollupViewRepositoryInterface
{
    public function findOne(string $vendorId, string $periodType, string $periodValue): ?OrderMetricsRollupView;

    public function upsert(string $vendorId, string $periodType, string $periodValue, callable $mutator): OrderMetricsRollupView;

    public function listByVendorAndPeriod(string $vendorId, ?string $periodType = null): array;
}
