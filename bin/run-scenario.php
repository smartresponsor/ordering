#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Read scenario (.json) and print steps; execute fault scripts and link to SLO gate.
 * This is a scaffold: integrate with your metrics source for real measurement.
 */
$scenario = $argv[1] ?? 'scenario/db-outage.json';
if (!file_exists($scenario)) {
    fwrite(STDERR, sprintf("Scenario not found: %s\n", $scenario));
    exit(2);
}

$rawScenario = file_get_contents($scenario);
$config = is_string($rawScenario) ? json_decode($rawScenario, true) : null;
if (!is_array($config)) {
    fwrite(STDERR, "Invalid scenario json\n");
    exit(2);
}

$experiment = is_array($config['experiment'] ?? null) ? $config['experiment'] : [];
$name = (string)($experiment['name'] ?? 'unnamed');
$fault = (string)($experiment['fault'] ?? '');
$params = is_array($experiment['params'] ?? null) ? $experiment['params'] : [];
$duration = (int)($experiment['duration_s'] ?? 60);

fwrite(STDOUT, sprintf("Scenario: %s\n", $name));

$runCommand = static function (string $command): void {
    fwrite(STDOUT, sprintf("+ %s\n", $command));
    passthru($command, $exitCode);
    if (0 !== $exitCode) {
        exit($exitCode);
    }
};

switch ($fault) {
    case 'netem':
        $delay = (int)($params['delay_ms'] ?? 150);
        $loss = (int)($params['loss_pct'] ?? 2);
        $runCommand(sprintf('DELAY_MS=%d LOSS_PCT=%d ./bin/fault-netem.sh', $delay, $loss));
        break;

    case 'kill_db':
        $faultDuration = (int)($params['dur'] ?? 90);
        $runCommand(sprintf('DUR=%d ./bin/fault-kill-db.sh', $faultDuration));
        break;

    case 'kill_worker':
        $runCommand('./bin/fault-kill-worker.sh');
        break;

    default:
        fwrite(STDOUT, sprintf("Unknown fault: %s\n", $fault));
        exit(2);
}

fwrite(STDOUT, sprintf("Hold for duration: %ds\n", $duration));
sleep($duration);

$p95 = (int)(getenv('P95_MS') ?: 230);
$errorRate = (float)(getenv('ERROR_RATE_PCT') ?: 0.2);

fwrite(STDOUT, json_encode([
        'p95_ms' => $p95,
        'error_rate_pct' => $errorRate,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);

$targetP95 = 250;
$errorBudget = 0.5;
$failed = false;

if ($p95 > $targetP95) {
    fwrite(STDOUT, "FAIL p95\n");
    $failed = true;
}

if ($errorRate > $errorBudget) {
    fwrite(STDOUT, "FAIL error_rate\n");
    $failed = true;
}

passthru('./bin/fault-netem-undo.sh');
exit($failed ? 1 : 0);
