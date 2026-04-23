#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Service\Security\Order\DlqConsole;
use App\Service\Security\Order\FileDlqRepository;
use App\ValueObject\Archival\Order\AuditLog;

require __DIR__ . '/../vendor/autoload.php';

$cmd = $argv[1] ?? 'list';
$repo = new FileDlqRepository(__DIR__ . '/../var/dlq.ndjson');
$audit = new AuditLog(__DIR__ . '/../var/audit.log');
$ui = new DlqConsole($repo, $audit);

if ($cmd === 'seed') {
    $ui->seed();
    exit(0);
}
if ($cmd === 'list') {
    $filter = [];
    foreach ($argv as $arg) {
        if (str_starts_with($arg, '--provider=')) {
            $filter['provider'] = substr($arg, 11);
        }
        if (str_starts_with($arg, '--reason=')) {
            $filter['reason'] = substr($arg, 9);
        }
    }
    $ui->showList($filter);
    exit(0);
}
if ($cmd === 'requeue') {
    $id = 'UNKNOWN';
    foreach ($argv as $arg) {
        if (str_starts_with($arg, '--id=')) {
            $id = substr($arg, 5);
        }
    }
    $ui->requeue($id);
    exit(0);
}
if ($cmd === 'discard') {
    $id = 'UNKNOWN';
    $reason = 'unspecified';
    foreach ($argv as $arg) {
        if (str_starts_with($arg, '--id=')) {
            $id = substr($arg, 5);
        }
        if (str_starts_with($arg, '--reason=')) {
            $reason = substr($arg, 9);
        }
    }
    $ui->discard($id, $reason);
    exit(0);
}

echo "Usage: dlq-console.php seed|list|requeue --id=...|discard --id=... --reason=...\n";
