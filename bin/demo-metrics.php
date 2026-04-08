#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

$console = __DIR__ . '/console';
$format = $argv[1] ?? 'json';

$command = sprintf(
    'php %s order:metrics:export --format=%s',
    escapeshellarg($console),
    escapeshellarg($format),
);

passthru($command, $exitCode);
exit($exitCode);
