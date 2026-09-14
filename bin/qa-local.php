#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;

$steps = [
    [
        'nameEntity' => 'Composer audit',
        'command' => ['composer', 'audit', '--no-interaction'],
        'optional' => false,
    ],
    [
        'nameEntity' => 'PHP lint',
        'command' => [PHP_BINARY, '-l', 'src/Controller/OrderManagementController.php'],
        'optional' => false,
    ],
    [
        'nameEntity' => 'YAML lint',
        'command' => [PHP_BINARY, 'bin/console', 'lint:yaml', 'config', '--parse-tags'],
        'optional' => false,
    ],
    [
        'nameEntity' => 'Twig lint',
        'command' => [PHP_BINARY, 'bin/console', 'lint:twig', 'templates'],
        'optional' => false,
    ],
    [
        'nameEntity' => 'Container lint',
        'command' => [PHP_BINARY, 'bin/console', 'lint:container'],
        'optional' => false,
    ],
    [
        'nameEntity' => 'Schema validate',
        'command' => [PHP_BINARY, 'bin/console', 'doctrine:schema:validate', '--skip-sync', '-vvv'],
        'optional' => false,
    ],
    [
        'nameEntity' => 'Unit tests',
        'command' => [PHP_BINARY, 'vendor/bin/phpunit', '--configuration', 'phpunit.xml.dist', '--testsuite', 'OrderFast'],
        'optional' => false,
    ],
    [
        'nameEntity' => 'Functional tests',
        'command' => [PHP_BINARY, 'vendor/bin/phpunit', '--configuration', 'phpunit.xml.dist', '--testsuite', 'OrderFullStack'],
        'optional' => false,
    ],
    [
        'nameEntity' => 'Importmap audit',
        'command' => [PHP_BINARY, 'bin/console', 'importmap:audit'],
        'optional' => true,
    ],
    [
        'nameEntity' => 'Gitleaks',
        'command' => ['gitleaks', 'detect', '--no-banner', '--source', '.'],
        'optional' => true,
    ],
    [
        'nameEntity' => 'Semgrep',
        'command' => ['semgrep', 'scan', '--config', 'auto'],
        'optional' => true,
    ],
];

foreach ($steps as $step) {
    echo PHP_EOL . '==> ' . $step['nameEntity'] . PHP_EOL;

    if ($step['optional'] && !commandIsAvailable($step['command'])) {
        echo 'SKIPPED: tool or command not available' . PHP_EOL;
        continue;
    }

    $process = new Process($step['command']);
    $process->setTimeout(null);
    $exitCode = $process->run(static function (string $type, string $buffer): void {
        fwrite(Process::ERR === $type ? STDERR : STDOUT, $buffer);
    });

    if (0 !== $exitCode) {
        fwrite(STDERR, 'FAILED: ' . $step['nameEntity'] . PHP_EOL);
        exit($exitCode);
    }
}

echo PHP_EOL . 'QA pipeline completed successfully.' . PHP_EOL;

/** @param list<string> $command */
function commandIsAvailable(array $command): bool
{
    if (in_array('importmap:audit', $command, true)) {
        $process = new Process([PHP_BINARY, 'bin/console', 'list', '--raw']);
        $process->run();
        $consoleCommands = preg_split('/\R/', trim($process->getOutput())) ?: [];

        return $process->isSuccessful() && in_array('importmap:audit', $consoleCommands, true);
    }

    $binary = $command[0];

    if (PHP_BINARY === $binary) {
        return true;
    }

    return binaryIsAvailable($binary);
}

function binaryIsAvailable(string $binary): bool
{
    $path = getenv('PATH');
    if (false === $path || '' === $path) {
        return false;
    }

    $extensions = [''];
    if ('Windows' === PHP_OS_FAMILY) {
        $pathExt = getenv('PATHEXT');
        $extensions = false !== $pathExt && '' !== $pathExt
            ? preg_split('/;/', $pathExt) ?: ['.EXE', '.BAT', '.CMD', '.COM']
            : ['.EXE', '.BAT', '.CMD', '.COM'];
    }

    foreach (explode(PATH_SEPARATOR, $path) as $directory) {
        foreach ($extensions as $extension) {
            $candidate = rtrim($directory, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$binary.$extension;
            if (is_file($candidate) && is_executable($candidate)) {
                return true;
            }
        }
    }

    return false;
}
