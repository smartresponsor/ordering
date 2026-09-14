#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;

$console = __DIR__ . '/console';
if (!is_file($console)) {
    fwrite(STDERR, "bin/console not found\n");
    exit(1);
}

$limit = $argv[1] ?? getenv('ORDER_PAYMENTS_BATCH') ?: '100';

$cmd = [
    PHP_BINARY,
    $console,
    'order:outbox:dispatch',
    '--limit=' . $limit,
];

$process = new Process($cmd);
$process->setTimeout(null);
$exitCode = $process->run(static function (string $type, string $buffer): void {
    fwrite(Process::ERR === $type ? STDERR : STDOUT, $buffer);
});

exit($exitCode);
