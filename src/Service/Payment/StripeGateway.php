<?php

declare(strict_types=1);

namespace App\Service\Payment;

use App\Ordering\Entity\Order\OrderEntity;
use App\ServiceInterface\Payment\PaymentGatewayInterface;

final class StripeGateway implements PaymentGatewayInterface
{
    /**
     * @throws \Exception
     */
    public function charge(OrderEntity $order, int $amount): string
    {
        return 'ch_'.bin2hex(random_bytes(6));
    }
}
