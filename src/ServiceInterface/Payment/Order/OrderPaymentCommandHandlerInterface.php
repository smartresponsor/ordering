<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ServiceInterface\Payment\Order;

use App\Message\Command\Order\OrderPaymentCommand;
use App\ServiceInterface\Payment\Order\PaymentGatewayInterface;
use App\Service\Payment\Order\PaymentService;
use App\Service\Tx\TransactionalEventPublisher;

interface OrderPaymentCommandHandlerInterface
{
    public function __construct(
        PaymentService $service,
        TransactionalEventPublisher $publisher,
        PaymentGatewayInterface $gateway,
    );

    public function __invoke(OrderPaymentCommand $cmd): void;
}
