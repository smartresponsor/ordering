<?php

declare(strict_types=1);

namespace App\Message\Command\Order;

require_once __DIR__.'/../../../Service/Security/Order/RecalculateOrderPricingCommand.php';

if (!class_exists(__NAMESPACE__.'\\RecalculateOrderPricingCommand', false)) {
    class_alias(\App\Service\Security\Order\RecalculateOrderPricingCommand::class, __NAMESPACE__.'\\RecalculateOrderPricingCommand');
}
