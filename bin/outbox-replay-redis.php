#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Infra\Persistence\PdoFactory;
use SmartResponsor\Order\Infra\Redis\RedisFactory;

$cfg = json_decode(file_get_contents(__DIR__.'/../config/outbox/redis.json'), true);
$rps = (int)($cfg['replay']['rps'] ?? 10);
$pdo = PdoFactory::fromEnv(getenv('DB_URL') ?: 'postgres://user:pass@localhost:5432/smartresponsor');
$r = RedisFactory::fromConfig($cfg['redis'] ?? []);
$dlq = ($cfg['queue']['dlq'] ?? 'outbox:dlq');
$seen = []; $windowStart = microtime(true); $sentInWindow = 0;

function rateGate(int $rps, float &$winStart, int &$sent){
  $now = microtime(true);
  if ($now - $winStart >= 1.0) { $winStart = $now; $sent = 0; }
  if ($sent >= $rps) { usleep(100000); return rateGate($rps, $winStart, $sent); }
  $sent++;
}

while ($raw = $r->lPop($dlq)) {
  rateGate($rps, $windowStart, $sentInWindow);
  $msg = json_decode($raw, true) ?: [];
  $id = $msg['id'] ?? '';
  if (!$id || isset($seen[$id])) continue; // dedupe
  $seen[$id] = true;
  // Push back to main queue
  $r->rPush($cfg['queue']['name'], json_encode($msg));
  echo "[replay] $id\n";
}
