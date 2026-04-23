#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

$console = __DIR__ . '/console';
$batch = max(1, (int)($argv[1] ?? 100));

passthru(sprintf('php %s order:outbox:replay --limit=%d', escapeshellarg($console), $batch), $exitCode);

exit($exitCode);
