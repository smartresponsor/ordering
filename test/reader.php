#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * CLI smoke check for the bundled NDJSON sample fixture.
 */
$fixture = __DIR__ . '/../example/sample.ndjson';
$lines = file($fixture, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
$count = 0;
foreach ($lines as $line) {
    json_decode($line, true, 512, JSON_THROW_ON_ERROR);
    ++$count;
}
assert($count >= 2);
echo "OK\n";
