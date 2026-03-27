<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\ServiceInterface\Order\TransactionMiddlewareInterface;
use Doctrine\ORM\EntityManagerInterface;

final class TransactionMiddleware implements TransactionMiddlewareInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    /**
     * @template T
     *
     * @param callable(EntityManagerInterface):T $fn
     *
     * @return T
     */
    public function run(callable $fn)
    {
        $this->em->beginTransaction();
        try {
            $result = $fn($this->em);
            $this->em->flush();
            $this->em->commit();

            return $result;
        } catch (\Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}
