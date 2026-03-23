<?php

declare(strict_types=1);
/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp
 */

namespace App\ServiceInterface\Order;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;

interface DomainEventsToOutboxSubscriberInterface
{
    public function getSubscribedEvents(): array;

    public function onFlush(OnFlushEventArgs $args): void;

    public function postFlush(PostFlushEventArgs $args): void;
}
