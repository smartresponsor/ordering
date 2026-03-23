<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Order;

use ApiPlatform\Metadata\Operation;
use Symfony\Component\Messenger\MessageBusInterface;

interface OrderDeleteProcessorInterface
{
    public function __construct(MessageBusInterface $bus);

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed;
}
