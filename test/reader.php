#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Import\NdjsonReader;
$f = __DIR__ . '/../example/sample.ndjson';
$r = new NdjsonReader($f);
$c = 0; foreach ($r->iter(1) as $row) { $c++; }
assert($c >= 2);
echo "OK\n";
