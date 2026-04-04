<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

interface OrderPlaceProcessorInterface
{
    public function __construct(MessageBusInterface $bus, EntityManagerInterface $em);

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed;
}
