<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use App\Entity\Order\IdempotencyKey;
use App\Repository\Order\IdempotencyKeyRepository;
use App\ServiceInterface\Security\Order\IdempotencyServiceInterface;

final readonly class IdempotencyService implements IdempotencyServiceInterface
{
    public function __construct(private IdempotencyKeyRepository $repo)
    {
    }

    public function checkAndStore(string $key): bool
    {
        if ($this->repo->exists($key)) {
            return false;
        }
        $this->repo->save(new IdempotencyKey($key));

        return true;
    }
}
