#!/usr/bin/env php
<?php
$region = getenv('REGION') ?: 'us';
$file = __DIR__.'/../var/router/canary.json';
$cfg = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$cfg[$region] = ['provider'=>null,'pct'=>0];
file_put_contents($file, json_encode($cfg, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
echo "canary off: $region\n";
