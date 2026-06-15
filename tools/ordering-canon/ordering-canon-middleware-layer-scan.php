<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$options = getopt('', ['write::']);

$legacy = [
    'src/Service/Security/Order/CspMiddleware.php',
    'src/Service/Security/Order/IdempotencyMiddleware.php',
    'src/Service/Security/Order/TransactionMiddleware.php',
    'src/ServiceInterface/Security/Order/CspMiddlewareInterface.php',
    'src/ServiceInterface/Security/Order/IdempotencyMiddlewareInterface.php',
    'src/ServiceInterface/Security/Order/TransactionMiddlewareInterface.php',
];

$canonical = [
    'src/Middleware/Http/Order/OrderCspMiddleware.php',
    'src/Messenger/Middleware/IdempotencyMiddleware.php',
    'src/Service/Tx/TransactionMiddleware.php',
    'src/Http/Idempotency/IdempotencyMiddleware.php',
];

$result = [
    'component' => 'Ordering',
    'scan' => 'middleware-layer',
    'legacy_existing' => [],
    'canonical_existing' => [],
];

foreach ($legacy as $path) {
    if (is_file($root.'/'.$path)) {
        $result['legacy_existing'][] = $path;
    }
}

foreach ($canonical as $path) {
    if (is_file($root.'/'.$path)) {
        $result['canonical_existing'][] = $path;
    }
}

$json = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL;

if (isset($options['write']) && is_string($options['write']) && '' !== $options['write']) {
    $target = $root.'/'.$options['write'];
    $dir = dirname($target);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($target, $json);
}

echo $json;
