#!/usr/bin/env php
<?php

declare(strict_types=1);

$steps = [
    [
        'name' => 'Composer audit',
        'command' => 'composer audit --no-interaction',
        'optional' => false,
    ],
    [
        'name' => 'PHP lint',
        'command' => 'php -l src/Controller/OrderManagementController.php',
        'optional' => false,
    ],
    [
        'name' => 'YAML lint',
        'command' => 'php bin/console lint:yaml config',
        'optional' => false,
    ],
    [
        'name' => 'Twig lint',
        'command' => 'php bin/console lint:twig templates',
        'optional' => false,
    ],
    [
        'name' => 'Container lint',
        'command' => 'php bin/console lint:container',
        'optional' => false,
    ],
    [
        'name' => 'Schema validate',
        'command' => 'php bin/console doctrine:schema:validate -vvv',
        'optional' => false,
    ],
    [
        'name' => 'Unit tests',
        'command' => 'php vendor/bin/phpunit --configuration phpunit.xml.dist --testsuite OrderFast',
        'optional' => false,
    ],
    [
        'name' => 'Functional tests',
        'command' => 'php vendor/bin/phpunit --configuration phpunit.xml.dist --testsuite OrderFullStack',
        'optional' => false,
    ],
    [
        'name' => 'Importmap audit',
        'command' => 'php bin/console importmap:audit',
        'optional' => true,
    ],
    [
        'name' => 'Gitleaks',
        'command' => 'gitleaks detect --no-banner --source .',
        'optional' => true,
    ],
    [
        'name' => 'Semgrep',
        'command' => 'semgrep scan --config auto',
        'optional' => true,
    ],
];

foreach ($steps as $step) {
    echo PHP_EOL.'==> '.$step['name'].PHP_EOL;

    if ($step['optional'] && !commandIsAvailable($step['command'])) {
        echo 'SKIPPED: tool or command not available'.PHP_EOL;
        continue;
    }

    passthru($step['command'], $exitCode);

    if (0 !== $exitCode) {
        fwrite(STDERR, 'FAILED: '.$step['name'].PHP_EOL);
        exit($exitCode);
    }
}

echo PHP_EOL.'QA pipeline completed successfully.'.PHP_EOL;

function commandIsAvailable(string $command): bool
{
    if (str_contains($command, 'importmap:audit')) {
        exec('php bin/console list --raw', $consoleCommands, $consoleExit);

        return 0 === $consoleExit && in_array('importmap:audit', $consoleCommands, true);
    }

    $binary = explode(' ', trim($command))[0];

    if ('php' === $binary || 'composer' === $binary) {
        return true;
    }

    $where = strtoupper(substr(PHP_OS_FAMILY, 0, 3)) === 'WIN' ? 'where' : 'command -v';
    exec($where.' '.$binary, $output, $exitCode);

    return 0 === $exitCode;
}
