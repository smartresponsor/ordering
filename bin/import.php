#!/usr/bin/env php
<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * This file is part of SmartResponsor (Order domain).
 */

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;

$console = __DIR__ . '/console';
$projectRoot = dirname(__DIR__);
$listProcess = new Process([PHP_BINARY, $console, 'list', '--raw']);
$listProcess->run();
$commandsOutput = 0 === $listProcess->getExitCode()
    ? preg_split('/\R/', trim($listProcess->getOutput())) ?: []
    : [];

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
$process = new Process([PHP_BINARY, $console, $resolvedCommand, ...$arguments]);
$process->setTimeout(null);
$exitCode = $process->run(static function (string $type, string $buffer): void {
    fwrite(Process::ERR === $type ? STDERR : STDOUT, $buffer);
});

exit($exitCode);
