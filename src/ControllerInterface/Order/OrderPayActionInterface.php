<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ControllerInterface\Order;

interface OrderPayActionInterface
{
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator);

    public function __invoke(string $id, Request $request): JsonResponse;
}
