<?php

declare(strict_types=1);

namespace App\Entity;

require_once __DIR__.'/Order/OrderRefundLedger.php';

if (!class_exists(__NAMESPACE__.'\\OrderRefundLedger', false)) {
    class_alias(Order\OrderRefundLedger::class, __NAMESPACE__.'\\OrderRefundLedger');
}
