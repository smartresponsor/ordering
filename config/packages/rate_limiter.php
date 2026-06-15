<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Yaml\Yaml;

return static function (ContainerConfigurator $c): void {
    $orderingRoot = dirname(__DIR__, 2);
    $runtimePath = $orderingRoot.'/config/component/runtime.yaml';
    $runtime = is_file($runtimePath) ? Yaml::parseFile($runtimePath) : [];
    $runtime = is_array($runtime) ? $runtime : [];

    $writeLimit = (int) ($runtime['ordering_rate_limit_api_write_limit'] ?? 60);
    $writeIntervalMinutes = (int) ($runtime['ordering_rate_limit_api_write_interval_minutes'] ?? 1);
    $readLimit = (int) ($runtime['ordering_rate_limit_api_read_limit'] ?? 600);
    $readIntervalMinutes = (int) ($runtime['ordering_rate_limit_api_read_interval_minutes'] ?? 1);

    $c->extension('framework', [
        'rate_limiter' => [
            'api' => [
                'policy' => 'token_bucket',
                'limit' => $writeLimit,
                'rate' => ['interval' => sprintf('%d minute%s', $writeIntervalMinutes, 1 === $writeIntervalMinutes ? '' : 's'), 'amount' => $writeLimit],
            ],
            'api_read' => [
                'policy' => 'sliding_window',
                'limit' => $readLimit,
                'interval' => sprintf('%d minute%s', $readIntervalMinutes, 1 === $readIntervalMinutes ? '' : 's'),
            ],
        ],
    ]);
};
