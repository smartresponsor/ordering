#!/usr/bin/env php
<?php
$redis = new Redis(); $redis->connect('127.0.0.1',6379);
$keys = $redis->keys('budget:*');
foreach ($keys as $k) { $redis->del($k); }
echo "budget keys cleared\n";
