<?php

declare(strict_types=1);

use App\Kernel;

$autoload = dirname(__DIR__).'/vendor/autoload.php';
if (!is_file($autoload)) {
    throw new RuntimeException('Missing vendor/autoload.php. Run composer install before phpunit.');
}

require $autoload;

if (class_exists(Symfony\Component\Dotenv\Dotenv::class) && is_file(dirname(__DIR__).'/.env')) {
    (new Symfony\Component\Dotenv\Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if (class_exists(Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport::class) && !class_exists(Symfony\Component\Messenger\Transport\InMemoryTransport::class)) {
    class_alias(Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport::class, Symfony\Component\Messenger\Transport\InMemoryTransport::class);
}

$_SERVER['APP_ENV'] ??= $_ENV['APP_ENV'] ?? 'test';
$_ENV['APP_ENV'] = (string) $_SERVER['APP_ENV'];
$_SERVER['APP_DEBUG'] ??= $_ENV['APP_DEBUG'] ?? '1';
$_ENV['APP_DEBUG'] = (string) $_SERVER['APP_DEBUG'];
$_SERVER['KERNEL_CLASS'] ??= $_ENV['KERNEL_CLASS'] ?? Kernel::class;
$_ENV['KERNEL_CLASS'] = (string) $_SERVER['KERNEL_CLASS'];

$testDatabase = dirname(__DIR__).'/var/test-'.getmypid().'.db';
$testDatabaseDsn = 'sqlite:///'.str_replace('\\', '/', $testDatabase);
$_SERVER['DATABASE_URL'] = $_ENV['DATABASE_URL'] = $testDatabaseDsn;
$_SERVER['PLATFORM_DATA_DATABASE'] = $_ENV['PLATFORM_DATA_DATABASE'] = $testDatabaseDsn;
if (is_file($testDatabase)) {
    @unlink($testDatabase);
}

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
