#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';
use SmartResponsor\Order\Import\IdempotencyIndex;
$idx = new IdempotencyIndex(sys_get_temp_dir().'/idx.json');
assert($idx->add('A') === true);
assert($idx->add('A') === false);
echo "OK\n";
