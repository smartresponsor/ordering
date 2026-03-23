<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\ControllerInterface\Order;

use ApiPlatform\Symfony\Validator\ValidatorInterface;
use App\ApiResource\Order\PricingConvertOutput;
use App\Service\Order\AdvancedPriceCalculator;
use Symfony\Component\HttpFoundation\Request;

interface ApiPricingConvertControllerInterface
{
    public function __construct(
        AdvancedPriceCalculator $calc,
        ValidatorInterface $validator,
    );

    public function __invoke(Request $request): PricingConvertOutput;
}
