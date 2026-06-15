<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$out = null;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--write=')) {
        $out = substr($arg, 8);
    }
}

$scanPaths = [
    'src/Service/Security/Order',
    'src/ServiceInterface/Security/Order',
    'src/Service/Outbox',
    'src/Repository/Outbox',
    'src/MessageHandler',
];

$residual = [];
$unknownOutboxSymbol = [];
foreach ($scanPaths as $scanPath) {
    $absolute = $root . DIRECTORY_SEPARATOR . $scanPath;
    if (!is_dir($absolute)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($absolute, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || 'php' !== $file->getExtension()) {
            continue;
        }

        $relative = str_replace('\', '/', substr($file->getPathname(), strlen($root) + 1));
        $contents = file_get_contents($file->getPathname()) ?: '';

        if (str_contains($relative, 'Service/Security/Order/Outbox') || str_contains($relative, 'ServiceInterface/Security/Order/Outbox')) {
            $residual[] = $relative;
        }

        if (preg_match('/(?<!Order)OutboxMessageEntity::class|new\s+OutboxMessageEntity\s*\(/', $contents)) {
            $unknownOutboxSymbol[] = $relative;
        }
    }
}

$result = [
    'component' => 'Ordering',
    'wave' => 12,
    'residual_legacy_outbox_security_files' => array_values(array_unique($residual)),
    'unknown_outbox_message_symbol_files' => array_values(array_unique($unknownOutboxSymbol)),
    'status' => ([] === $residual && [] === $unknownOutboxSymbol) ? 'clean' : 'needs-followup',
];

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
if (null !== $out) {
    $target = str_starts_with($out, DIRECTORY_SEPARATOR) ? $out : $root . DIRECTORY_SEPARATOR . $out;
    if (!is_dir(dirname($target))) {
        mkdir(dirname($target), 0775, true);
    }
    file_put_contents($target, $json);
}

echo $json;
