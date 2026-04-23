#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

$console = __DIR__ . '/console';
$cycles = max(1, (int)($argv[1] ?? 1));

for ($cycle = 0; $cycle < $cycles; ++$cycle) {
    passthru(sprintf('php %s order:outbox:run', escapeshellarg($console)), $exitCode);

    if (0 !== $exitCode) {
        exit($exitCode);
    }

    if ($cycle + 1 < $cycles) {
        usleep(200000);
    }
}
