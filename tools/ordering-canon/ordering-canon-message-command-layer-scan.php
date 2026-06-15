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
$patterns = ['*Command.php', '*Handler.php'];
$legacy = [];
foreach ($patterns as $pattern) {
    foreach (glob($legacyDir.'/'.$pattern) ?: [] as $file) {
        $legacy[] = str_replace($root.'/', '', $file);
    }
}
sort($legacy);

$bridges = [];
foreach (['src/Message', 'src/MessageHandler'] as $relativeDir) {
    $dir = $root.'/'.$relativeDir;
    if (!is_dir($dir)) {
        continue;
    }
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if (!$file->isFile() || 'php' !== $file->getExtension()) {
            continue;
        }
        $content = file_get_contents($file->getPathname()) ?: '';
        if (str_contains($content, 'App\\Service\\Security\\Order') || str_contains($content, 'Service/Security/Order')) {
            $bridges[] = str_replace($root.'/', '', $file->getPathname());
        }
    }
}
sort($bridges);

$result = [
    'legacy_service_security_order_commands_or_handlers' => $legacy,
    'canonical_message_layer_legacy_bridges' => $bridges,
    'legacy_count' => count($legacy),
    'bridge_count' => count($bridges),
];

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
if (false === $json) {
    throw new RuntimeException('Unable to encode scan result.');
}

if (null !== $out) {
    $target = $root.'/'.ltrim($out, '/\\');
    $parent = dirname($target);
    if (!is_dir($parent)) {
        mkdir($parent, 0775, true);
    }
    file_put_contents($target, $json."\n");
}

echo $json."\n";
