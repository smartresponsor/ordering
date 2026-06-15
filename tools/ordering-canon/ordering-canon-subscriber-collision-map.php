<?php

declare(strict_types=1);

$root = getcwd();
$out = null;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--write=')) {
        $out = substr($arg, 8);
    }
}

$legacyDir = $root.'/src/Service/Subscriber/Order';
$canonicalDir = $root.'/src/Subscriber/Event/Order';
$rows = [];

$legacyFiles = is_dir($legacyDir) ? glob($legacyDir.'/*.php') ?: [] : [];
foreach ($legacyFiles as $legacyFile) {
    $nameEntity = basename($legacyFile);
    $canonicalFile = $canonicalDir.'/'.$nameEntity;
    $rows[] = [
        'legacy_path' => 'src/Service/Subscriber/Order/'.$nameEntity,
        'canonical_path' => is_file($canonicalFile) ? 'src/Subscriber/Event/Order/'.$nameEntity : null,
        'has_canonical_counterpart' => is_file($canonicalFile),
        'legacy_sha1' => sha1_file($legacyFile),
        'canonical_sha1' => is_file($canonicalFile) ? sha1_file($canonicalFile) : null,
        'same_content' => is_file($canonicalFile) && sha1_file($legacyFile) === sha1_file($canonicalFile),
    ];
}

$result = [
    'component' => 'Ordering',
    'wave' => 5,
    'purpose' => 'Subscriber collision map for Service/Subscriber/Order retirement.',
    'legacy_count' => count($legacyFiles),
    'rows' => $rows,
];

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL;
if (null !== $out) {
    $dir = dirname($out);
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    file_put_contents($out, $json);
}

echo $json;
