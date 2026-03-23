<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ControllerInterface\Order;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface DlqControllerInterface
{
    public function list(Request $request): Response;

    public function requeueOne(Request $request, string $id): Response;

    public function requeueBatch(Request $request): Response;
}
