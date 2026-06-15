<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$options = getopt('', ['write::']);

$legacyRoots = [
    'src/EventListener',
    'src/EventSubscriber',
    'src/Service/Subscriber',
];

$findPhp = static function (string $dir) use ($root): array {
    $absolute = $root . DIRECTORY_SEPARATOR . $dir;
    if (!is_dir($absolute)) {
        return [];
    }

    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($absolute, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || !$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $files[] = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
    }

    sort($files);
    return $files;
};

$result = [
    'component' => 'Ordering',
    'wave' => 6,
    'legacy_event_layer_roots' => [],
    'total_legacy_php_files' => 0,
    'canonical_targets' => [
        'src/Subscriber',
        'src/Listener',
    ],
];

foreach ($legacyRoots as $legacyRoot) {
    $files = $findPhp($legacyRoot);
    $result['legacy_event_layer_roots'][$legacyRoot] = $files;
    $result['total_legacy_php_files'] += count($files);
}

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;

if (isset($options['write']) && is_string($options['write']) && $options['write'] !== '') {
    $target = $root . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $options['write']);
    $dir = dirname($target);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    file_put_contents($target, $json);
}

echo $json;
