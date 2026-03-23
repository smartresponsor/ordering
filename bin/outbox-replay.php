#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Infra\Persistence\PdoFactory;
use SmartResponsor\Order\Infra\Outbox\DLQRepository;

$db = getenv('DB_URL') ?: 'postgres://user:pass@localhost:5432/smartresponsor';
$pdo = PdoFactory::fromEnv($db);
$dlq = new DLQRepository($pdo); $dlq->ensure();
$rows = $dlq->fetchBatch(100);
foreach ($rows as $r) {
  try {
    // TODO: publish to Kafka/SNS or back to outbox; here we just print
    echo '[replay] '.$r['id'].' '.$r['topic']."\n";
    $dlq->delete($r['id']);
  } catch (Throwable $e) {
    fwrite(STDERR, "replay fail ".$r['id'].": ".$e->getMessage()."\n");
  }
}
