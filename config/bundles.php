<?php

declare(strict_types=1);

// Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp

$bundles = [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
];

foreach ([
    'Doctrine\Bundle\DoctrineBundle\DoctrineBundle',
    'Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle',
    'Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle',
    'Sentry\Symfony\Bundle\SentryBundle',
    'Symfony\Bundle\TwigBundle\TwigBundle',
] as $optionalBundle) {
    if (class_exists($optionalBundle)) {
        $bundles[$optionalBundle] = ['all' => true];
    }
}

return $bundles;
