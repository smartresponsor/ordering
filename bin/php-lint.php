#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Process\Process;

$root = dirname(__DIR__);
$directories = ['src', 'config', 'tests'];
$failures = [];
$checked = 0;

foreach ($directories as $directory) {
    $path = $root.DIRECTORY_SEPARATOR.$directory;
    if (!is_dir($path)) {
        continue;
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
    );

    foreach ($files as $file) {
        if (!$file instanceof SplFileInfo || !$file->isFile() || 'php' !== strtolower($file->getExtension())) {
            continue;
        }

        ++$checked;
        $process = new Process([PHP_BINARY, '-l', $file->getPathname()]);
        $process->run();
        if (!$process->isSuccessful()) {
            $output = trim($process->getOutput().PHP_EOL.$process->getErrorOutput());
            $failures[] = $file->getPathname().PHP_EOL.$output;
        }
    }
}

printf("PHP lint checked %d files.%s", $checked, PHP_EOL);
if ([] !== $failures) {
    fwrite(STDERR, implode(PHP_EOL.PHP_EOL, $failures).PHP_EOL);
    exit(1);
}

exit(0);
