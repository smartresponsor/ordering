<?php

declare(strict_types=1);

namespace App\Service\Order\Adapter\Payment;

final class StripeGateway extends \App\Integration\Payment\StripeStubGateway implements PaymentGatewayInterface
{
}
