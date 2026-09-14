#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

$console = __DIR__ . '/console';
$cycles = max(1, (int)($argv[1] ?? 1));

for ($cycle = 0; $cycle < $cycles; ++$cycle) {
    $process = new Process([PHP_BINARY, $console, 'order:outbox:run']);
    $process->setTimeout(null);
    $exitCode = $process->run(static function (string $type, string $buffer): void {
        fwrite(Process::ERR === $type ? STDERR : STDOUT, $buffer);
    });

    if (0 !== $exitCode) {
        exit($exitCode);
    }

    if ($cycle + 1 < $cycles) {
        usleep(200000);
    }
}
