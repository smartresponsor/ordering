<?php

declare(strict_types=1);

// Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp

$bundles = [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
];

foreach ([
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class,
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class,
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class,
    Sentry\Symfony\Bundle\SentryBundle::class,
    Symfony\Bundle\TwigBundle\TwigBundle::class,
] as $optionalBundle) {
    if (class_exists($optionalBundle)) {
        $bundles[$optionalBundle] = ['all' => true];
    }
}

return $bundles;
