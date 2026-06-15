<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$out = null;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--write=')) {
        $out = substr($arg, 8);
    }
}

$legacyDir = $root.'/src/Service/Security/Order';
$canonical = [
    'OrderResource.php' => 'src/ApiResource/View/Order/OrderResource.php',
    'OrderPartialPaymentProcessor.php' => 'src/Api/Processor/OrderPartialPaymentProcessor.php',
    'OrderRefundProcessor.php' => 'src/Api/Processor/OrderRefundProcessor.php',
];

$rows = [];
foreach ($canonical as $legacyFile => $canonicalPath) {
    $legacyPath = $legacyDir.'/'.$legacyFile;
    $targetPath = $root.'/'.$canonicalPath;
    $rows[] = [
        'legacy' => 'src/Service/Security/Order/'.$legacyFile,
        'legacy_exists' => is_file($legacyPath),
        'canonical' => $canonicalPath,
        'canonical_exists' => is_file($targetPath),
        'status' => is_file($legacyPath) && is_file($targetPath) ? 'duplicate_can_retire' : (is_file($legacyPath) ? 'legacy_without_canonical' : 'closed'),
    ];
}

$result = [
    'component' => 'Ordering',
    'wave' => 11,
    'focus' => 'api_processor_resource_layer_closure',
    'checked_at' => gmdate('c'),
    'rows' => $rows,
];

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL;
if ($out !== null && $out !== '') {
    $path = str_starts_with($out, '/') ? $out : $root.'/'.$out;
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($path, $json);
}

echo $json;
