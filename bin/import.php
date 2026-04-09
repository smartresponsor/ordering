#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

$console = __DIR__ . '/console';
$projectRoot = dirname(__DIR__);
$commandsOutput = [];
$commandsExitCode = 0;
@exec(sprintf('php %s list --raw 2>NUL', escapeshellarg($console)), $commandsOutput, $commandsExitCode);

$knownCandidates = [
    'order:import',
    'app:order:import',
];

$resolvedCommand = null;
foreach ($knownCandidates as $candidate) {
    if (in_array($candidate, $commandsOutput, true)) {
        $resolvedCommand = $candidate;
        break;
    }
}

if (null === $resolvedCommand) {
    fwrite(STDERR, "Legacy bin/import.php importer has been retired from the current App runtime.\n");
    fwrite(STDERR, "No canonical Symfony import command is registered in this slice.\n");
    fwrite(STDERR, "Expected future command names: order:import or app:order:import\n");
    fwrite(STDERR, "Project root: {$projectRoot}\n");
    exit(1);
}

$arguments = array_slice($argv, 1);
$command = sprintf('php %s %s', escapeshellarg($console), $resolvedCommand);

foreach ($arguments as $argument) {
    $command .= ' ' . escapeshellarg($argument);
}

passthru($command, $exitCode);
exit($exitCode);
