<?php

declare(strict_types=1);

// Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp

$bundles = [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    ApiPlatform\Symfony\Bundle\ApiPlatformBundle::class => ['all' => true],
    Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle::class => ['all' => true],
];

foreach ([
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class,
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class,
    Sentry\Symfony\Bundle\SentryBundle::class,
] as $optionalBundle) {
    if (class_exists($optionalBundle)) {
        $bundles[$optionalBundle] = ['all' => true];
    }
}

return $bundles;
