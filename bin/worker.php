#!/usr/bin/env php
<?php

declare(strict_types=1);

$console = __DIR__.'/console';
if (!is_file($console)) {
    fwrite(STDERR, "bin/console not found\n");
    exit(1);
}

$tenant = $argv[1] ?? getenv('ORDER_WORKER_TENANT') ?: 'tenant_a';
$topic = $argv[2] ?? getenv('ORDER_WORKER_TOPIC') ?: 'order_events';
$concurrency = $argv[3] ?? getenv('ORDER_WORKER_CONCURRENCY') ?: '4';
$cycles = $argv[4] ?? getenv('ORDER_WORKER_CYCLES') ?: '100';

$cmd = [
    PHP_BINARY,
    $console,
    'order:outbox:process',
    '--tenant='.$tenant,
    '--topic='.$topic,
    '--concurrency='.$concurrency,
    '--cycles='.$cycles,
];

passthru(implode(' ', array_map('escapeshellarg', $cmd)), $exitCode);
exit($exitCode);
