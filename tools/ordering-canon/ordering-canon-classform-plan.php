<?php

declare(strict_types=1);

/**
 * Ordering canon class-form planner.
 *
 * This tool is intentionally read-only. It scans PHP classes and produces a
 * deterministic inventory of current layer, detected class form, naming issue,
 * and a proposed canonical target path/namespace for review before any move wave.
 */
final class OrderingCanonClassFormPlan
{
    /** @var array<string,string> */
    private const LAYER_SUFFIX = [
        'Controller' => 'Controller',
        'Command' => 'Command',
        'Event' => 'Event',
        'Entity' => 'Entity',
        'Factory' => 'Factory',
        'Form' => 'Type',
        'Middleware' => 'Middleware',
        'Repository' => 'Repository',
        'Service' => 'Service',
    ];

    /** @var list<string> */
    private const INTERFACE_LAYERS = [
        'AuditInterface',
        'CommandInterface',
        'DLQInterface',
        'DLXInterface',
        'DemoInterface',
        'RepositoryInterface',
        'ServiceInterface',
    ];

    /** @return list<array<string,mixed>> */
    public function scan(string $root): array
    {
        $src = $root.'/src';
        if (!is_dir($src)) {
            throw new RuntimeException('src directory not found: '.$src);
        }

        $rows = [];
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $file) {
            if (!$file instanceof SplFileInfo || !$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $path = str_replace('\\', '/', $file->getPathname());
            $relative = ltrim(substr($path, strlen($root)), '/');
            $code = (string) file_get_contents($path);
            $rows[] = $this->inspect($relative, $code);
        }

        usort($rows, static fn (array $a, array $b): int => strcmp((string) $a['path'], (string) $b['path']));

        return $rows;
    }

    /** @return array<string,mixed> */
    private function inspect(string $relative, string $code): array
    {
        preg_match('/^namespace\s+([^;]+);/m', $code, $namespaceMatch);
        preg_match('/\b(final\s+|abstract\s+)?(class|interface|trait|enum)\s+(\w+)/', $code, $classMatch);

        $namespace = $namespaceMatch[1] ?? '';
        $kind = $classMatch[2] ?? '';
        $class = $classMatch[3] ?? basename($relative, '.php');
        $parts = explode('/', $relative);
        $currentLayer = $parts[1] ?? '';
        $detectedForm = $this->detectForm($relative, $code, $class, $kind, $currentLayer);
        $subPath = $this->canonicalSubPath($parts, $detectedForm);
        $expectedSuffix = $this->expectedSuffix($detectedForm, $kind);
        $expectedName = $this->expectedName($class, $expectedSuffix, $kind);
        $targetPath = $this->targetPath($detectedForm, $subPath, $expectedName);
        $targetNamespace = $this->targetNamespace($targetPath);
        $issues = [];

        if ($namespace === '') {
            $issues[] = 'missing_namespace';
        }
        if ($kind === '') {
            $issues[] = 'missing_classlike_declaration';
        }
        if ($expectedSuffix !== '' && $expectedName !== $class) {
            $issues[] = 'missing_or_wrong_suffix';
        }
        if ($targetPath !== $relative) {
            $issues[] = 'wrong_layer_or_path';
        }
        if ($targetNamespace !== '' && $namespace !== '' && $targetNamespace !== $namespace) {
            $issues[] = 'namespace_drift';
        }
        if (str_contains($relative, '/Order/Order/') || preg_match('#^src/(Order|Ordering)(/|$)#', $relative)) {
            $issues[] = 'domain_named_folder_depth_risk';
        }
        if ($currentLayer === 'Service' && $detectedForm !== 'Service') {
            $issues[] = 'non_service_inside_service_layer';
        }

        return [
            'path' => $relative,
            'namespace' => $namespace,
            'kind' => $kind,
            'class' => $class,
            'current_layer' => $currentLayer,
            'detected_form' => $detectedForm,
            'expected_name' => $expectedName,
            'target_path' => $targetPath,
            'target_namespace' => $targetNamespace,
            'issues' => $issues,
            'action' => $issues === [] ? 'keep' : 'review_or_move',
        ];
    }

    private function detectForm(string $path, string $code, string $class, string $kind, string $currentLayer): string
    {
        if ($kind === 'interface') {
            if (str_ends_with($currentLayer, 'Interface')) {
                return $currentLayer;
            }

            return 'ServiceInterface';
        }

        $suffixMap = [
            'Authenticator' => 'Authenticator',
            'CommandHandler' => 'MessageHandler',
            'Handler' => 'MessageHandler',
            'Controller' => 'Controller',
            'Action' => 'Controller',
            'Command' => 'Command',
            'Console' => 'Command',
            'Entity' => 'Entity',
            'Event' => 'Event',
            'Factory' => 'Factory',
            'Listener' => 'Listener',
            'Middleware' => 'Middleware',
            'Processor' => 'Processor',
            'Provider' => 'Provider',
            'Projector' => 'Projector',
            'Repository' => 'Repository',
            'Resource' => 'ApiResource',
            'Subscriber' => 'Subscriber',
            'Transformer' => 'Transformer',
            'Validator' => 'Validator',
            'Voter' => 'Voter',
        ];

        foreach ($suffixMap as $suffix => $layer) {
            if (str_ends_with($class, $suffix)) {
                return $layer;
            }
        }

        if (str_contains($code, '#[AsMessageHandler') || str_contains($code, 'AsMessageHandler')) {
            return 'MessageHandler';
        }
        if (str_contains($code, 'extends AbstractController')) {
            return 'Controller';
        }
        if (str_contains($code, 'extends AbstractType')) {
            return 'Form';
        }
        if (str_contains($code, 'implements EventSubscriberInterface')) {
            return 'Subscriber';
        }
        if (str_contains($code, 'implements MiddlewareInterface')) {
            return 'Middleware';
        }

        return $currentLayer !== '' ? $currentLayer : 'Service';
    }

    private function canonicalSubPath(array $parts, string $detectedForm): string
    {
        $currentLayer = $parts[1] ?? '';
        $inner = array_slice($parts, 2, -1);

        if ($currentLayer === 'Api' && isset($inner[0]) && in_array($inner[0], ['Controller', 'Processor', 'Provider', 'Resource', 'State'], true)) {
            array_shift($inner);
        }

        if ($currentLayer === 'Service' && isset($inner[0]) && $inner[0] === 'Subscriber' && $detectedForm === 'Subscriber') {
            array_shift($inner);
        }

        return implode('/', $inner);
    }

    private function expectedSuffix(string $form, string $kind): string
    {
        if ($kind === 'interface' || str_ends_with($form, 'Interface')) {
            return 'Interface';
        }

        return self::LAYER_SUFFIX[$form] ?? '';
    }

    private function expectedName(string $class, string $suffix, string $kind): string
    {
        if ($suffix === '' || $class === '' || str_ends_with($class, $suffix)) {
            return $class;
        }

        if ($kind === 'interface' && str_ends_with($class, 'Contract')) {
            return substr($class, 0, -8).'Interface';
        }

        return $class.$suffix;
    }

    private function targetPath(string $form, string $subPath, string $expectedName): string
    {
        $safeSubPath = trim($subPath, '/');
        $prefix = 'src/'.$form;

        return $prefix.($safeSubPath !== '' ? '/'.$safeSubPath : '').'/'.$expectedName.'.php';
    }

    private function targetNamespace(string $targetPath): string
    {
        $dir = dirname($targetPath);
        if ($dir === '.' || $dir === 'src') {
            return 'App';
        }

        return 'App\\'.str_replace('/', '\\', substr($dir, 4));
    }
}

function argValue(string $nameEntity, ?string $default = null): ?string
{
    global $argv;
    foreach ($argv as $arg) {
        if (str_starts_with($arg, $nameEntity.'=')) {
            return substr($arg, strlen($nameEntity) + 1);
        }
    }

    return $default;
}

$root = realpath(argValue('--root', getcwd()) ?? getcwd());
if ($root === false) {
    throw new RuntimeException('Invalid root');
}

$planner = new OrderingCanonClassFormPlan();
$rows = $planner->scan($root);
$out = argValue('--write');
$csv = argValue('--csv');

if ($out !== null) {
    $target = $root.'/'.ltrim($out, '/');
    if (!is_dir(dirname($target))) {
        mkdir(dirname($target), 0775, true);
    }
    file_put_contents($target, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

if ($csv !== null) {
    $target = $root.'/'.ltrim($csv, '/');
    if (!is_dir(dirname($target))) {
        mkdir(dirname($target), 0775, true);
    }
    $fh = fopen($target, 'wb');
    fputcsv($fh, ['path', 'class', 'kind', 'current_layer', 'detected_form', 'expected_name', 'target_path', 'issues', 'action']);
    foreach ($rows as $row) {
        fputcsv($fh, [
            $row['path'],
            $row['class'],
            $row['kind'],
            $row['current_layer'],
            $row['detected_form'],
            $row['expected_name'],
            $row['target_path'],
            implode('|', $row['issues']),
            $row['action'],
        ]);
    }
    fclose($fh);
}

$summary = [
    'total' => count($rows),
    'with_issues' => count(array_filter($rows, static fn (array $row): bool => $row['issues'] !== [])),
    'non_service_inside_service_layer' => count(array_filter($rows, static fn (array $row): bool => in_array('non_service_inside_service_layer', $row['issues'], true))),
    'wrong_layer_or_path' => count(array_filter($rows, static fn (array $row): bool => in_array('wrong_layer_or_path', $row['issues'], true))),
    'missing_or_wrong_suffix' => count(array_filter($rows, static fn (array $row): bool => in_array('missing_or_wrong_suffix', $row['issues'], true))),
];

echo json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL;
