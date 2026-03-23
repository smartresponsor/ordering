#!/usr/bin/env php
<?php
/**
 * Read scenario (.json) and print steps; execute fault scripts and link to SLO gate.
 * This is a scaffold: integrate with your metrics source for real measurement.
 */
$scenario = $argv[1] ?? 'scenario/db-outage.json';
if (!file_exists($scenario)) { fwrite(STDERR, "Scenario not found: $scenario\n"); exit(2); }
$conf = json_decode(file_get_contents($scenario), true);
if (!is_array($conf)) { fwrite(STDERR, "Invalid scenario json\n"); exit(2); }
echo "Scenario: ".$conf['experiment']['name']."\n";
$fault = $conf['experiment']['fault'];
$params = $conf['experiment']['params'] ?? [];
$duration = (int)($conf['experiment']['duration_s'] ?? 60);

function sh($cmd){ echo "+ $cmd\n"; passthru($cmd, $code); if ($code!==0) { exit($code); } }

switch ($fault) {
  case 'netem':
    $delay = (int)($params['delay_ms'] ?? 150);
    $loss = (int)($params['loss_pct'] ?? 2);
    sh("DELAY_MS=$delay LOSS_PCT=$loss ./bin/fault-netem.sh");
    break;
  case 'kill_db':
    $dur = (int)($params['dur'] ?? 90);
    sh("DUR=$dur ./bin/fault-kill-db.sh");
    break;
  case 'kill_worker':
    sh("./bin/fault-kill-worker.sh");
    break;
  default:
    echo "Unknown fault: $fault\n"; exit(2);
}

echo "Hold for duration: {$duration}s\n";
sleep($duration);

// TODO: collect real metrics; read from artifact if provided
$p95 = (int) getenv('P95_MS') ?: 230;
$err = (float) getenv('ERROR_RATE_PCT') ?: 0.2;

echo json_encode(['p95_ms'=>$p95,'error_rate_pct'=>$err], JSON_PRETTY_PRINT)."\n";
$target_p95 = 250; $budget = 0.5;
$fail = 0;
if ($p95 > $target_p95) { echo "FAIL p95\n"; $fail=1; }
if ($err > $budget) { echo "FAIL error_rate\n"; $fail=1; }

// Undo netem if applied
@passthru("./bin/fault-netem-undo.sh");

exit($fail);
