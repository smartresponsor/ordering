#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Service\Http\Order\Redactor;

function argvValue(array $argv, string $name, ?string $default = null): ?string
{
    $index = array_search($name, $argv, true);

    return false !== $index && isset($argv[$index + 1]) ? $argv[$index + 1] : $default;
}

$inputPath = argvValue($argv, '--input');
$outputPath = argvValue($argv, '--output');
$policyPath = __DIR__ . '/../config/pii/pii-policy.json';

if (null === $inputPath) {
    fwrite(STDERR, "Usage: purge-pii.php --input <json-file> [--output <json-file>]\n");
    exit(2);
}

$raw = @file_get_contents($inputPath);
if (false === $raw || '' === $raw) {
    fwrite(STDERR, "Input file not found or empty: {$inputPath}\n");
    exit(1);
}

$payload = json_decode($raw, true);
if (!is_array($payload)) {
    fwrite(STDERR, "Input must be a JSON object or array: {$inputPath}\n");
    exit(1);
}

$policyRaw = @file_get_contents($policyPath);
$policy = is_string($policyRaw) ? json_decode($policyRaw, true) : [];
$policyFields = is_array($policy['fields'] ?? null) ? $policy['fields'] : [];

$redactor = new Redactor();
$redacted = $redactor->redactArray($payload);
foreach ($policyFields as $field) {
    if (is_string($field) && array_key_exists($field, $redacted)) {
        $redacted[$field] = '***';
    }
}

$json = json_encode($redacted, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
if (false === $json) {
    fwrite(STDERR, "Failed to encode redacted payload\n");
    exit(1);
}

if (null !== $outputPath) {
    file_put_contents($outputPath, $json . "\n");
    echo "Redacted payload written to {$outputPath}\n";
    exit(0);
}

echo $json . "\n";
