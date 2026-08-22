<?php

declare(strict_types=1);

namespace App\Ordering\Form\Config;

final class OrderingRateLimitsConfigData
{
    public string $apiWriteLimit = '60';

    public string $apiWriteIntervalMinutes = '1';

    public string $apiReadLimit = '600';

    public string $apiReadIntervalMinutes = '1';
}
