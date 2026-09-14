#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;

/**
 * Read scenario (.json) and print steps; execute fault scripts and link to SLO gate.
 * This is a scaffold: integrate with your metrics source for real measurement.
 */
$scenario = $argv[1] ?? 'deploy/chaos/scenario/db-outage.json';
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
$nameEntity = (string)($experiment['nameEntity'] ?? 'unnamed');
$fault = (string)($experiment['fault'] ?? '');
$params = is_array($experiment['params'] ?? null) ? $experiment['params'] : [];
$duration = (int)($experiment['duration_s'] ?? 60);

fwrite(STDOUT, sprintf("Scenario: %s\n", $nameEntity));

/**
 * @param list<string> $command
 * @param array<string,string> $env
 */
$runCommand = static function (array $command, array $env = []): void {
    fwrite(STDOUT, '+ ' . implode(' ', $command) . PHP_EOL);
    $process = new Process($command, null, $env);
    $process->setTimeout(null);
    $exitCode = $process->run(static function (string $type, string $buffer): void {
        fwrite(Process::ERR === $type ? STDERR : STDOUT, $buffer);
    });
    if (0 !== $exitCode) {
        exit($exitCode);
    }
};

switch ($fault) {
    case 'netem':
        $delay = (int)($params['delay_ms'] ?? 150);
        $loss = (int)($params['loss_pct'] ?? 2);
        $runCommand(
            ['sh', __DIR__ . '/fault-netem.sh'],
            ['DELAY_MS' => (string)$delay, 'LOSS_PCT' => (string)$loss],
        );
        break;

    case 'kill_db':
        $faultDuration = (int)($params['dur'] ?? 90);
        $runCommand(
            ['sh', __DIR__ . '/fault-kill-db.sh'],
            ['DUR' => (string)$faultDuration],
        );
        break;

    case 'kill_worker':
        $runCommand(['sh', __DIR__ . '/fault-kill-worker.sh']);
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

$undoProcess = new Process(['sh', __DIR__ . '/fault-netem-undo.sh']);
$undoProcess->setTimeout(null);
$undoProcess->run(static function (string $type, string $buffer): void {
    fwrite(Process::ERR === $type ? STDERR : STDOUT, $buffer);
});

exit($failed ? 1 : 0);
