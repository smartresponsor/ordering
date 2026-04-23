#!/usr/bin/env php
<?php

declare(strict_types=1);

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

passthru(implode(' ', array_map('escapeshellarg', $cmd)), $exitCode);
exit($exitCode);
