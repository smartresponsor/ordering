#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use SmartResponsor\Order\Infra\Persistence\PdoFactory;
use SmartResponsor\Order\Infra\Redis\RedisFactory;

$cfg = json_decode(file_get_contents(__DIR__.'/../config/outbox/redis.json'), true);
$pdo = PdoFactory::fromEnv(getenv('DB_URL') ?: 'postgres://user:pass@localhost:5432/smartresponsor');
$r = RedisFactory::fromConfig($cfg['redis'] ?? []);
$q = ($cfg['queue']['name'] ?? 'outbox:queue');
$dlq = ($cfg['queue']['dlq'] ?? 'outbox:dlq');
$maxAttempts = (int)($cfg['queue']['max_attempts'] ?? 5);

function deliver(array $msg, array $row): bool {
  // TODO: replace with Kafka/SNS/HTTP call. For now just prints.
  // Return false to simulate failure scenario based on topic/payload.
  echo "[deliver] {$msg['id']} {$msg['topic']}\n";
  return true;
}

while (true) {
  $raw = $r->lPop($q);
  if (!$raw) { usleep(200000); continue; }
  $msg = json_decode($raw, true) ?: [];
  if (empty($msg['id'])) continue;
  $id = $msg['id'];
  $row = $pdo->prepare('SELECT id, tenant_id, topic, payload, attempts FROM outbox WHERE id=:id');
  $row->execute([':id'=>$id]);
  $data = $row->fetch(PDO::FETCH_ASSOC);
  if (!$data) { continue; }
  $attempts = (int)($data['attempts'] ?? 0);
  try {
    $ok = deliver($msg, $data);
    if ($ok) {
      $pdo->prepare('DELETE FROM outbox WHERE id=:id')->execute([':id'=>$id]);
    } else {
      throw new RuntimeException('delivery failed');
    }
  } catch (Throwable $e) {
    $attempts++;
    $pdo->prepare('UPDATE outbox SET attempts=:a, last_error=:err, updated_at=now() WHERE id=:id')->execute([':a'=>$attempts, ':err'=>$e->getMessage(), ':id'=>$id]);
    if ($attempts >= $maxAttempts) {
      $r->rPush($dlq, json_encode($msg)); // to Redis DLQ
    } else {
      // exponential backoff via delayed requeue (simple sleep delay here; replace with ZSET if needed)
      $r->rPush($q, json_encode($msg));
    }
  }
}
