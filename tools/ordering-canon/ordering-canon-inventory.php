#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
chdir($root);

$options = getopt('', ['write::', 'fail-on-root-php']);
$writePath = is_string($options['write'] ?? null) ? $options['write'] : null;
$failOnRootPhp = array_key_exists('fail-on-root-php', $options);

/** @return list<string> */
function phpFiles(string $directory): array
{
    if (!is_dir($directory)) {
        return [];
    }

    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = str_replace('\\', '/', $file->getPathname());
        }
    }

    sort($files);

    return $files;
}

/** @return array{namespace: string|null, kind: string|null, name: string|null, alias_only: bool} */
function phpDeclaration(string $file): array
{
    $content = file_get_contents($file, false, null, 0, 24000);
    if ($content === false) {
        return ['namespace' => null, 'kind' => null, 'nameEntity' => null, 'alias_only' => false];
    }

    preg_match('/^namespace\s+([^;]+);/m', $content, $namespace);
    preg_match('/^(?:final\s+|abstract\s+|readonly\s+)*\s*(class|interface|trait|enum)\s+(\w+)/m', $content, $declaration);

    return [
        'namespace' => $namespace[1] ?? null,
        'kind' => $declaration[1] ?? null,
        'nameEntity' => $declaration[2] ?? null,
        'alias_only' => $declaration === [] && str_contains($content, 'class_alias('),
    ];
}

$srcFiles = phpFiles('src');
$testFiles = phpFiles('tests');
$rootPhp = [];
foreach (glob('*.php') ?: [] as $file) {
    if ($file !== 'php-cs-fixer.dist.php' && $file !== '.php-cs-fixer.php') {
        $rootPhp[] = $file;
    }
}
sort($rootPhp);

$srcLayers = [];
$basenameMismatches = [];
$aliasOnly = [];
$missingDeclarations = [];

foreach ($srcFiles as $file) {
    $parts = explode('/', $file);
    $layer = $parts[1] ?? '(root)';
    $srcLayers[$layer] = ($srcLayers[$layer] ?? 0) + 1;

    $declaration = phpDeclaration($file);
    if ($declaration['alias_only']) {
        $aliasOnly[] = $file;
        continue;
    }

    if ($declaration['nameEntity'] === null) {
        $missingDeclarations[] = $file;
        continue;
    }

    if (basename($file) !== $declaration['nameEntity'] . '.php') {
        $basenameMismatches[] = [
            'file' => $file,
            'declared' => $declaration['nameEntity'],
        ];
    }
}
arsort($srcLayers);

$result = [
    'repository' => 'Ordering',
    'generated_at_utc' => gmdate(DATE_ATOM),
    'autoload_namespace' => 'App\\',
    'src_php_files' => count($srcFiles),
    'tests_php_files' => count($testFiles),
    'root_php_files' => $rootPhp,
    'src_layers_by_php_count' => $srcLayers,
    'src_class_file_basename_mismatches' => $basenameMismatches,
    'src_alias_only_files' => $aliasOnly,
    'src_missing_declaration_files' => $missingDeclarations,
];

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
echo $json;

if ($writePath !== null && $writePath !== '') {
    $directory = dirname($writePath);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    file_put_contents($writePath, $json);
}

if ($failOnRootPhp && $rootPhp !== []) {
    fwrite(STDERR, sprintf("Root PHP files detected: %d\n", count($rootPhp)));
    exit(1);
}

