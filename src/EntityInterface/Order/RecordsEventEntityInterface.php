<?php

declare(strict_types=1);

namespace App\Ordering\EntityInterface\Order;

use App\Ordering\EntityInterface\Event\Order\RecordsEventEntityInterface as CanonicalRecordsEventEntityInterface;

class_alias(CanonicalRecordsEventEntityInterface::class, __NAMESPACE__.'\\RecordsEventEntityInterface');
