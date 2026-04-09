#!/usr/bin/env php
<?php

declare(strict_types=1);

$region = getenv('REGION') ?: 'us';
$file = __DIR__.'/../var/router/canary.json';
$directory = dirname($file);

if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
    fwrite(STDERR, sprintf("Unable to create directory: %s\n", $directory));
    exit(1);
}

$rawConfig = file_exists($file) ? file_get_contents($file) : false;
$config = is_string($rawConfig) && '' !== $rawConfig ? json_decode($rawConfig, true) : [];
if (!is_array($config)) {
    fwrite(STDERR, sprintf("Invalid canary config JSON in %s\n", $file));
    exit(1);
}

$config[$region] = [
    'provider' => null,
    'pct' => 0,
];

$result = file_put_contents($file, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);
if (false === $result) {
    fwrite(STDERR, sprintf("Unable to write canary config to %s\n", $file));
    exit(1);
}

fwrite(STDOUT, sprintf("canary off: %s\n", $region));
