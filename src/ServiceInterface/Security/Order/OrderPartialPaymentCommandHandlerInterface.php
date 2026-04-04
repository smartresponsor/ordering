<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Security\Order;

use App\Message\Legacy\Order\OrderPartialPaymentCommand;
use App\Contract\Gateway\Order\OrderPaymentGatewayInterface;
use App\Service\Payment\Order\PartialPaymentService;
use App\Service\Security\Order\TransactionalEventPublisher;

interface OrderPartialPaymentCommandHandlerInterface
{
    public function __construct(
        PartialPaymentService $service,
        PaymentGatewayInterface $gateway,
        TransactionalEventPublisher $publisher,
    );

    public function __invoke(OrderPartialPaymentCommand $cmd): void;
}
