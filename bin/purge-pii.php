#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Infra\Persistence\PdoFactory;

$db = getenv('DB_URL') ?: 'postgres://user:pass@localhost:5432/smartresponsor';
$pdo = PdoFactory::fromEnv($db);

$pdo->exec("UPDATE order_entity SET meta = jsonb_strip_nulls(meta - 'pii' || jsonb_build_object('pii_redacted', true)) WHERE status IN ('closed','returned','canceled') AND updated_at < now() - interval '30 days'");

$pdo->exec("UPDATE order_entity SET customer_id = 'anon_' || substr(encode(digest(id || customer_id, 'sha256'), 'hex'),1,12) WHERE updated_at < now() - interval '180 days' AND customer_id IS NOT NULL");

echo "PII purge done\n";
