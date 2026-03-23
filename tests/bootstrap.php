<?php

declare(strict_types=1);

use App\Kernel;

$autoload = dirname(__DIR__).'/vendor/autoload.php';
if (!is_file($autoload)) {
    throw new RuntimeException('Missing vendor/autoload.php. Run composer install before phpunit.');
}

require $autoload;

$_SERVER['APP_ENV'] ??= $_ENV['APP_ENV'] ?? 'test';
$_ENV['APP_ENV'] = (string) $_SERVER['APP_ENV'];
$_SERVER['APP_DEBUG'] ??= $_ENV['APP_DEBUG'] ?? '1';
$_ENV['APP_DEBUG'] = (string) $_SERVER['APP_DEBUG'];
$_SERVER['KERNEL_CLASS'] ??= $_ENV['KERNEL_CLASS'] ?? Kernel::class;
$_ENV['KERNEL_CLASS'] = (string) $_SERVER['KERNEL_CLASS'];

if (!class_exists(Kernel::class)) {
    return;
}

$needsFullStack = false;
foreach ($_SERVER['argv'] ?? [] as $arg) {
    if (str_contains((string) $arg, 'OrderFullStack')) {
        $needsFullStack = true;
        break;
    }
}

if (!$needsFullStack) {
    return;
}

if (!class_exists(Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class)) {
    return;
}

$kernel = new Kernel('test', true);
$kernel->boot();
