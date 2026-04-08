#!/usr/bin/env php
<?php

declare(strict_types=1);

$console = __DIR__ . '/console';

if (!is_file($console)) {
    fwrite(STDERR, "Unable to locate Symfony console at {$console}.\n");
    exit(1);
}

$batch = getenv('ORDER_OUTBOX_BATCH');
$command = sprintf('php %s order:outbox:dispatch', escapeshellarg($console));

if (is_string($batch) && '' !== trim($batch)) {
    $command .= sprintf(' --batch=%d', max(1, (int) $batch));
}

passthru($command, $exitCode);

exit((int) $exitCode);
