#!/usr/bin/env php
<?php
$provider = getenv('PROVIDER') ?: 'adyen';
$region = getenv('REGION') ?: 'us';
$pct = (int)(getenv('PCT') ?: 5);
$file = __DIR__.'/../var/router/canary.json';
@mkdir(dirname($file), 0775, true);
$cfg = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$cfg[$region] = ['provider'=>$provider,'pct'=>$pct];
file_put_contents($file, json_encode($cfg, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
echo "canary set: $region $provider $pct%\n";
