<?php

declare(strict_types=1);

$root = getcwd();
$out = null;
foreach ($argv as $i => $arg) {
    if ($arg === '--write' && isset($argv[$i + 1])) {
        $out = $argv[$i + 1];
    }
}

$base = $root . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Service' . DIRECTORY_SEPARATOR . 'Security' . DIRECTORY_SEPARATOR . 'Order';
$rows = [];
if (is_dir($base)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }
        $path = str_replace($root . DIRECTORY_SEPARATOR, '', $file->getPathname());
        $nameEntity = $file->getBasename('.php');
        $form = 'service-candidate';
        foreach ([
            'Command' => 'command',
            'Handler' => 'handler',
            'Middleware' => 'middleware',
            'Listener' => 'listener',
            'Subscriber' => 'subscriber',
            'Processor' => 'processor',
            'Message' => 'message',
            'Repository' => 'repository',
            'Factory' => 'factory',
            'Resource' => 'api-resource',
            'Validator' => 'validator',
            'Policy' => 'policy',
            'Kernel' => 'kernel',
        ] as $suffix => $candidate) {
            if (str_ends_with($nameEntity, $suffix)) {
                $form = $candidate;
                break;
            }
        }
        $rows[] = [
            'path' => str_replace('\\', '/', $path),
            'class_basename' => $nameEntity,
            'detected_form' => $form,
            'canonical_pressure' => $form === 'service-candidate' ? 'review' : 'move-to-type-layer',
        ];
    }
}

$result = [
    'component' => 'Ordering',
    'scan' => 'service-security-form-scan',
    'target' => 'src/Service/Security/Order',
    'count' => count($rows),
    'items' => $rows,
];

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
if ($out !== null) {
    $dir = dirname($out);
    if ($dir !== '' && !is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($out, $json);
} else {
    echo $json;
}
