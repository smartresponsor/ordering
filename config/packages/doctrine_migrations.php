<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $c): void {
    $projectDir = '%kernel.project_dir%';

    $c->extension('doctrine_migrations', [
        'migrations_paths' => [
            'App\\Migrations' => $projectDir.'/migrations',
            'DoctrineMigrations' => $projectDir.'/migrations',
            'OrderComponent\\Order\\Migrations' => $projectDir.'/migrations',
            'OrderComponent\\Migrations' => $projectDir.'/migrations',
            'OrderComponent\\Migrations\\Order' => $projectDir.'/migrations/Order',
        ],
        'organize_migrations' => 'none',
    ]);
};
