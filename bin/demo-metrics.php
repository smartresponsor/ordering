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
$format = $argv[1] ?? 'json';

$process = new Process([PHP_BINARY, $console, 'order:metrics:export', '--format=' . $format]);
$process->setTimeout(null);
$exitCode = $process->run(static function (string $type, string $buffer): void {
    fwrite(Process::ERR === $type ? STDERR : STDOUT, $buffer);
});

exit($exitCode);
