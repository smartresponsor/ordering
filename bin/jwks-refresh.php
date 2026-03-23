#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';
use SmartResponsor\Order\Security\JWT\JwksCache;
$cfg = json_decode(file_get_contents(__DIR__.'/../config/security/jwt.json'), true);
$jwks = new JwksCache($cfg['jwks_url'] ?? '', __DIR__.'/../var/security/jwks.json', (int)($cfg['cache_ttl_sec'] ?? 900));
$jwks->refresh();
echo "jwks refreshed\n";
