#!/usr/bin/env php
<?php
// Test only: mint RS256 tokens from test key
$priv = file_get_contents(__DIR__.'/../tests/keys/test-private.pem');
$kid = $argv[1] ?? 'test1';
$iss = $argv[2] ?? 'https://issuer.example';
$aud = $argv[3] ?? 'smartresponsor';
$sub = $argv[4] ?? 'user_123';
$ttl = (int)($argv[5] ?? 300);
$nbfShift = (int)($argv[6] ?? 0); // seconds shift to future
$hdr = ['typ'=>'JWT','alg'=>'RS256','kid'=>$kid];
$now = time();
$pl = ['iss'=>$iss,'aud'=>$aud,'sub'=>$sub,'iat'=>$now,'nbf'=>$now + $nbfShift,'exp'=>$now + $ttl];
$h = rtrim(strtr(base64_encode(json_encode($hdr)), '+/', '-_'), '=');
$p = rtrim(strtr(base64_encode(json_encode($pl)), '+/', '-_'), '=');
$data = $h.'.'.$p;
openssl_sign($data, $sig, $priv, OPENSSL_ALGO_SHA256);
$s = rtrim(strtr(base64_encode($sig), '+/', '-_'), '=');
echo $data.'.'.$s."\n";
