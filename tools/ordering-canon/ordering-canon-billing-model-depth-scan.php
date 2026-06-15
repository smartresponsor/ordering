<?php

declare(strict_types=1);

/**
 * Scans Ordering billing value models for forbidden owner/domain depth.
 *
 * Canon rule: repository/domain token `Order` is forbidden at the third
 * nesting level under src, except for the explicit Entity exception.
 */

$root = dirname(__DIR__, 2);
$options = getopt('', ['write::']);

$legacyDir = $root . '/src/Model/Order/Billing';
$canonicalDir = $root . '/src/Model/Billing/Order';

$legacyFiles = [];
if (is_dir($legacyDir)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($legacyDir, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
            $legacyFiles[] = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
        }
    }
}

$canonicalFiles = [];
if (is_dir($canonicalDir)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($canonicalDir, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
            $canonicalFiles[] = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
        }
    }
}

sort($legacyFiles);
sort($canonicalFiles);

$result = [
    'component' => 'Ordering',
    'scan' => 'billing-model-depth',
    'legacyDirectory' => 'src/Model/Order/Billing',
    'canonicalDirectory' => 'src/Model/Billing/Order',
    'legacyCount' => count($legacyFiles),
    'canonicalCount' => count($canonicalFiles),
    'legacyFiles' => $legacyFiles,
    'canonicalFiles' => $canonicalFiles,
    'passed' => count($legacyFiles) === 0,
];

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

if (isset($options['write']) && is_string($options['write']) && $options['write'] !== '') {
    $target = $options['write'];
    if (!str_starts_with($target, DIRECTORY_SEPARATOR) && !preg_match('/^[A-Za-z]:[\\\/]/', $target)) {
        $target = $root . '/' . $target;
    }

    $directory = dirname($target);
    if (!is_dir($directory)) {
        mkdir($directory, 0775, true);
    }

    file_put_contents($target, $json);
}

echo $json;

exit($result['passed'] ? 0 : 1);
