#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../src/Security/JWT/JwksCache.php';
require __DIR__.'/../src/Security/JWT/JwtVerifier.php';

use SmartResponsor\Order\Security\JWT\JwksCache;
use SmartResponsor\Order\Security\JWT\JwtVerifier;

$cfg = json_decode(file_get_contents(__DIR__.'/../config/security/jwt.json'), true);
$cache = new JwksCache($cfg['jwks_url'], __DIR__.'/../var/security/jwks.json', 3600);
$ver = new JwtVerifier($cfg['issuer'], $cfg['audience'], $cache, (int)($cfg['leeway_sec'] ?? 30));

function t($name, $ok) { echo ($ok?"[PASS] ":"[FAIL] ").$name."\n"; return $ok; }

$pass = true;

// Prepare JWKS
@mkdir(__DIR__.'/../var/security', 0775, true);
copy(__DIR__.'/../tests/jwks/test-jwks.json', __DIR__.'/../var/security/jwks.json');

// 1) valid
$jwt = trim(shell_exec('php '.__DIR__.'/jwt-mint-test.php test1'));
$r = $ver->verify($jwt); $pass &= t('valid token', $r['ok']);

// 2) expired
$jwt = trim(shell_exec('php '.__DIR__.'/jwt-mint-test.php test1 https://issuer.example smartresponsor user_123 -10'));
$r = $ver->verify($jwt); $pass &= t('expired token', !$r['ok'] && $r['error']==='expired');

// 3) nbf future
$jwt = trim(shell_exec('php '.__DIR__.'/jwt-mint-test.php test1 https://issuer.example smartresponsor user_123 300 9999'));
$r = $ver->verify($jwt); $pass &= t('nbf future', !$r['ok'] && $r['error']==='nbf_future');

// 4) wrong audience
$jwt = trim(shell_exec('php '.__DIR__.'/jwt-mint-test.php test1 https://issuer.example WRONGAUD'));
$r = $ver->verify($jwt); $pass &= t('wrong audience', !$r['ok'] && $r['error']==='bad_aud');

// 5) wrong issuer
$jwt = trim(shell_exec('php '.__DIR__.'/jwt-mint-test.php test1 https://bad-issuer smartresponsor'));
$r = $ver->verify($jwt); $pass &= t('wrong issuer', !$r['ok'] && $r['error']==='bad_iss');

// 6) unknown kid/bad sig
$jwt = trim(shell_exec('php '.__DIR__.'/jwt-mint-test.php test2 https://issuer.example smartresponsor'));
$r = $ver->verify($jwt); $pass &= t('unknown kid', !$r['ok'] && ($r['error']==='kid_not_found' || $r['error']==='bad_signature'));

exit($pass ? 0 : 1);
