<?php

declare(strict_types=1);

namespace App\Saga;

require_once __DIR__.'/../Service/Workflow/Order/OrderSaga.php';

if (!class_exists(__NAMESPACE__.'\\OrderSaga', false)) {
    class_alias(\App\Service\Workflow\Order\OrderSaga::class, __NAMESPACE__.'\\OrderSaga');
}
