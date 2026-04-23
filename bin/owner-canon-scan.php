#!/usr/bin/env php
<?php
declare(strict_types=1);

$projectRoot = dirname(__DIR__);
chdir($projectRoot);

$options = getopt('', ['write::', 'fail-on-violations']);
$writePath = $options['write'] ?? null;
$failOnViolations = array_key_exists('fail-on-violations', $options);

/** @return list<string> */
function listDirs(string $absoluteRoot, string $relativePrefix): array
{
    if (!is_dir($absoluteRoot)) {
        return [];
    }

    $dirs = [];
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($absoluteRoot, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($it as $file) {
        if ($file->isDir()) {
            $path = str_replace('\\', '/', $file->getPathname());
            $relative = ltrim(substr($path, strlen($absoluteRoot)), '/');
            $dirs[] = $relativePrefix . ($relative !== '' ? '/' . $relative : '');
        }
    }

    sort($dirs);

    return $dirs;
}

/** @return list<string> */
function listPhpFiles(string $relativeRoot): array
{
    $absoluteRoot = getcwd() . '/' . trim($relativeRoot, '/');
    if (!is_dir($absoluteRoot)) {
        return [];
    }

    $files = [];
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($absoluteRoot, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($it as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = str_replace('\\', '/', $file->getPathname());
            $files[] = ltrim(substr($path, strlen(getcwd() . '/')), '/');
        }
    }

    sort($files);

    return $files;
}

/** @return array<string, list<string>> */
function collectViolations(): array
{
    $violations = [
        'root_forbidden' => [],
        'src_forbidden' => [],
        'src_competing_tree' => [],
        'order_dir_depth' => [],
        'tests_forbidden' => [],
        'todo_token' => [],
        'stub_token' => [],
        'empty_catch' => [],
    ];

    foreach (['legacy', 'todo'] as $path) {
        if (is_dir($path) || is_file($path)) {
            $violations['root_forbidden'][] = $path;
        }
    }

    foreach ([
                 'src/Port',
                 'src/Adaptor',
                 'src/Infra',
                 'src/opr',
                 'src/Order',
                 'src/OrderInterface',
                 'src/Ordering',
                 'src/OrderingInterface',
                 'src/Domain',
                 'src/DomainInterface',
             ] as $path) {
        if (is_dir($path)) {
            $violations['src_forbidden'][] = $path;
        }
    }

    if (is_dir('src/Interface')) {
        $violations['src_competing_tree'][] = 'src/Interface';
    }

    foreach (listDirs(getcwd() . '/src', 'src') as $dir) {
        if (str_contains($dir, '/Order/') && !str_starts_with($dir, 'src/Entity/Order')) {
            $violations['order_dir_depth'][] = $dir;
        }
        if (str_contains($dir, '/Ordering/') || str_starts_with($dir, 'src/Ordering')) {
            $violations['order_dir_depth'][] = $dir;
        }
    }

    foreach (['test', 'tests'] as $testRoot) {
        foreach (listDirs(getcwd() . '/' . $testRoot, $testRoot) as $dir) {
            if (
                str_contains($dir, '/Order/')
                || str_contains($dir, '/Ordering/')
                || str_starts_with($dir, $testRoot . '/Order')
                || str_starts_with($dir, $testRoot . '/Ordering')
            ) {
                $violations['tests_forbidden'][] = $dir;
            }
        }
    }

    foreach (listPhpFiles('src') as $file) {
        $content = file_get_contents($file);
        if ($content === false) {
            continue;
        }

        if (str_contains($content, 'TODO')) {
            $violations['todo_token'][] = $file;
        }
        if (preg_match('/stub/i', $content) === 1) {
            $violations['stub_token'][] = $file;
        }
        if (preg_match('/catch\s*\([^)]*\)\s*\{\s*}/', $content) === 1) {
            $violations['empty_catch'][] = $file;
        }
    }

    return array_map(static fn(array $items): array => array_values(array_unique($items)), $violations);
}

$violations = collectViolations();
$total = 0;
foreach ($violations as $items) {
    $total += count($items);
}

$result = [
    'workspace' => 'Ordering',
    'entity' => 'Order',
    'generated_at_utc' => gmdate(DATE_ATOM),
    'total_violations' => $total,
    'violations' => $violations,
];

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
echo $json;

if (is_string($writePath) && $writePath !== '') {
    $directory = dirname($writePath);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    file_put_contents($writePath, $json);
}

if ($failOnViolations && $total > 0) {
    fwrite(STDERR, sprintf('owner canon violations detected: %d%s', $total, PHP_EOL));
    exit(1);
}
