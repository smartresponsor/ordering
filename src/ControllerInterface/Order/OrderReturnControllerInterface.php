<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ControllerInterface\Order;

interface OrderReturnControllerInterface
{
    public function __construct(ReturnWorkflowService $workflow);

    public function create(string $id, Request $req): JsonResponse;
}
