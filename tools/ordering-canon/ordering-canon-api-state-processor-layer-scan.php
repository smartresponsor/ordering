<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacyRoot = $root . '/src/Service/Security/Order';
$targets = [
    'OrderDeleteProcessor.php',
    'OrderPatchProcessor.php',
    'OrderPlaceProcessor.php',
];

$residual = [];
foreach ($targets as $file) {
    $path = $legacyRoot . '/' . $file;
    if (is_file($path)) {
        $residual[] = 'src/Service/Security/Order/' . $file;
    }
}

$legacyInterfaces = [];
foreach ([
    'OrderDeleteProcessorInterface.php',
    'OrderPatchProcessorInterface.php',
    'OrderPlaceProcessorInterface.php',
] as $file) {
    $path = $root . '/src/ServiceInterface/Security/Order/' . $file;
    if (is_file($path)) {
        $legacyInterfaces[] = 'src/ServiceInterface/Security/Order/' . $file;
    }
}

$result = [
    'wave' => 13,
    'scope' => 'api-processor-state-layer-closure',
    'legacy_processor_residuals' => $residual,
    'legacy_processor_interface_residuals' => $legacyInterfaces,
    'canonical_processors' => [
        'src/Api/Processor/OrderDeleteProcessor.php' => is_file($root . '/src/Api/Processor/OrderDeleteProcessor.php'),
        'src/Api/Processor/OrderPatchProcessor.php' => is_file($root . '/src/Api/Processor/OrderPatchProcessor.php'),
        'src/Api/Processor/OrderPlaceProcessor.php' => is_file($root . '/src/Api/Processor/OrderPlaceProcessor.php'),
    ],
];

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
if (false === $json) {
    throw new RuntimeException('Failed to encode result.');
}

$args = $_SERVER['argv'] ?? [];
$write = null;
foreach ($args as $arg) {
    if (str_starts_with($arg, '--write=')) {
        $write = substr($arg, strlen('--write='));
    }
}

if (null !== $write && '' !== $write) {
    $target = $root . '/' . ltrim(str_replace('\\', '/', $write), '/');
    $dir = dirname($target);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    file_put_contents($target, $json . PHP_EOL);
}

echo $json . PHP_EOL;
