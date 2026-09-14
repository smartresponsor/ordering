#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;

$console = __DIR__ . '/console';

if (!is_file($console)) {
    fwrite(STDERR, "Unable to locate Symfony console at {$console}.\n");
    exit(1);
}

$batch = getenv('ORDER_OUTBOX_BATCH');
$command = [PHP_BINARY, $console, 'order:outbox:dispatch'];

if (is_string($batch) && '' !== trim($batch)) {
    $command[] = '--batch=' . max(1, (int)$batch);
}

$process = new Process($command);
$process->setTimeout(null);
$exitCode = $process->run(static function (string $type, string $buffer): void {
    fwrite(Process::ERR === $type ? STDERR : STDOUT, $buffer);
});

exit($exitCode);
