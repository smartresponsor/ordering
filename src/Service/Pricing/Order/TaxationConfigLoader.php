<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\ValueObject\Order\TaxRate;
use Symfony\Component\Yaml\Yaml;

class TaxationConfigLoader implements \App\ServiceInterface\Pricing\Order\TaxationConfigLoaderInterface
{
    private array $config;

    public function __construct(string $path)
    {
        $this->config = Yaml::parseFile($path)['taxation'] ?? [];
    }

    public function rounding(): int
    {
        return (int) ($this->config['rounding'] ?? 2);
    }

    public function defaultCurrency(): string
    {
        return (string) ($this->config['default_currency'] ?? 'USD');
    }

    public function rateFor(string $region, ?string $subregion = null): TaxRate
    {
        $rates = $this->config['rates'] ?? [];
        if (isset($rates[$region])) {
            if ($subregion && isset($rates[$region][$subregion])) {
                return new TaxRate((string) $rates[$region][$subregion]);
            }
            if (isset($rates[$region]['default'])) {
                return new TaxRate((string) $rates[$region]['default']);
            }
        }

        return new TaxRate('0');
    }
}
