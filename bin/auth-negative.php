#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

function base64UrlDecode(string $value): string
{
    $padding = 4 - (strlen($value) % 4);

    if ($padding < 4) {
        $value .= str_repeat('=', $padding);
    }

    return (string)base64_decode(strtr($value, '-_', '+/'), true);
}

/**
 * @return array{ok:bool,error?:string,claims?:array<string,mixed>}
 */
function verifyJwt(
    string $jwt,
    string $publicPem,
    string $issuer,
    string $audience,
    int    $leeway,
    string $expectedKid,
): array
{
    $parts = explode('.', $jwt);

    if (3 !== count($parts)) {
        return ['ok' => false, 'error' => 'malformed'];
    }

    [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
    $header = json_decode(base64UrlDecode($encodedHeader), true);
    $claims = json_decode(base64UrlDecode($encodedPayload), true);

    if (!is_array($header) || !is_array($claims)) {
        return ['ok' => false, 'error' => 'malformed'];
    }

    if (($header['kid'] ?? null) !== $expectedKid) {
        return ['ok' => false, 'error' => 'kid_not_found'];
    }

    $signature = base64UrlDecode($encodedSignature);
    $verified = openssl_verify(
        $encodedHeader . '.' . $encodedPayload,
        $signature,
        $publicPem,
        OPENSSL_ALGO_SHA256,
    );

    if (1 !== $verified) {
        return ['ok' => false, 'error' => 'bad_signature'];
    }

    $now = time();
    $exp = (int)($claims['exp'] ?? 0);
    $nbf = (int)($claims['nbf'] ?? 0);
    $iss = (string)($claims['iss'] ?? '');
    $aud = $claims['aud'] ?? '';

    if ($exp > 0 && $exp + $leeway < $now) {
        return ['ok' => false, 'error' => 'expired'];
    }

    if ($nbf > 0 && $nbf - $leeway > $now) {
        return ['ok' => false, 'error' => 'nbf_future'];
    }

    if ($iss !== $issuer) {
        return ['ok' => false, 'error' => 'bad_iss'];
    }

    if ($aud !== $audience) {
        return ['ok' => false, 'error' => 'bad_aud'];
    }

    return ['ok' => true, 'claims' => $claims];
}

function reportCase(string $nameEntity, bool $ok): bool
{
    echo ($ok ? '[PASS] ' : '[FAIL] ') . $nameEntity . PHP_EOL;

    return $ok;
}

$config = json_decode((string)file_get_contents(__DIR__ . '/../config/security/jwt.json'), true);

if (!is_array($config)) {
    fwrite(STDERR, "Unable to read config/security/jwt.json\n");
    exit(2);
}

$issuer = (string)($config['issuer'] ?? 'https://issuer.example');
$audience = (string)($config['audience'] ?? 'smartresponsor');
$leeway = (int)($config['leeway_sec'] ?? 30);
$publicPemPath = __DIR__ . '/../tests/keys/test-public.pem';
$publicPem = (string)file_get_contents($publicPemPath);

$pass = true;

$valid = trim((string)shell_exec('php ' . escapeshellarg(__DIR__ . '/jwt-mint-test.php') . ' test1'));
$result = verifyJwt($valid, $publicPem, $issuer, $audience, $leeway, 'test1');
$pass &= reportCase('valid token', true === $result['ok']);

$expired = trim((string)shell_exec(
    'php '
    . escapeshellarg(__DIR__ . '/jwt-mint-test.php')
    . ' test1 '
    . escapeshellarg($issuer)
    . ' '
    . escapeshellarg($audience)
    . ' user_123 -10',
));
$result = verifyJwt($expired, $publicPem, $issuer, $audience, $leeway, 'test1');
$pass &= reportCase('expired token', false === $result['ok'] && 'expired' === ($result['error'] ?? null));

$future = trim((string)shell_exec(
    'php '
    . escapeshellarg(__DIR__ . '/jwt-mint-test.php')
    . ' test1 '
    . escapeshellarg($issuer)
    . ' '
    . escapeshellarg($audience)
    . ' user_123 300 9999',
));
$result = verifyJwt($future, $publicPem, $issuer, $audience, $leeway, 'test1');
$pass &= reportCase('nbf future', false === $result['ok'] && 'nbf_future' === ($result['error'] ?? null));

$wrongAudience = trim((string)shell_exec(
    'php '
    . escapeshellarg(__DIR__ . '/jwt-mint-test.php')
    . ' test1 '
    . escapeshellarg($issuer)
    . ' WRONGAUD',
));
$result = verifyJwt($wrongAudience, $publicPem, $issuer, $audience, $leeway, 'test1');
$pass &= reportCase('wrong audience', false === $result['ok'] && 'bad_aud' === ($result['error'] ?? null));

$wrongIssuer = trim((string)shell_exec(
    'php '
    . escapeshellarg(__DIR__ . '/jwt-mint-test.php')
    . ' test1 https://bad-issuer '
    . escapeshellarg($audience),
));
$result = verifyJwt($wrongIssuer, $publicPem, $issuer, $audience, $leeway, 'test1');
$pass &= reportCase('wrong issuer', false === $result['ok'] && 'bad_iss' === ($result['error'] ?? null));

$unknownKid = trim((string)shell_exec(
    'php '
    . escapeshellarg(__DIR__ . '/jwt-mint-test.php')
    . ' test2 '
    . escapeshellarg($issuer)
    . ' '
    . escapeshellarg($audience),
));
$result = verifyJwt($unknownKid, $publicPem, $issuer, $audience, $leeway, 'test1');
$pass &= reportCase('unknown kid', false === $result['ok'] && 'kid_not_found' === ($result['error'] ?? null));

exit($pass ? 0 : 1);
