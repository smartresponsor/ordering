#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use SmartResponsor\Order\Import\{Importer,NdjsonReader,CsvReader,Validator,Mapper,ProgressStore,Reporter,IdempotencyIndex};

function argvFlag(string $name): bool { global $argv; return in_array($name, $argv, true); }
function argvValue(string $name, ?string $default=null): ?string { global $argv; $i = array_search($name, $argv, true); return ($i !== false && isset($argv[$i+1])) ? $argv[$i+1] : $default; }

$input = argvValue('--input'); if (!$input) { fwrite(STDERR, "--input is required\n"); exit(2); }
$format = strtolower(argvValue('--format','ndjson'));
$dry = argvFlag('--dry-run');
$base = dirname(__DIR__);
$progress = new ProgressStore($base.'/var/progress/checkpoint.json');
$report = new Reporter($base.'/var/report');
$index = new IdempotencyIndex($base.'/var/index/seen.json');

$mapFile = $base.'/config/map/status-map.json';
$map = json_decode(file_get_contents($mapFile), true);
$mapper = new Mapper($map);

$reader = match($format) {
  'ndjson' => new NdjsonReader($input),
  'csv' => new CsvReader($input),
  default => throw new RuntimeException('unsupported format')
};

$imp = new Importer($reader, new Validator(), $mapper, $progress, $report, $index, $dry);
$imp->run();
echo "DONE\n";
