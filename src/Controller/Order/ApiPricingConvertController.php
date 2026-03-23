<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use ApiPlatform\Symfony\Validator\ValidatorInterface;
use App\ApiResource\Order\PricingConvertInput;
use App\ApiResource\Order\PricingConvertOutput;
use App\Service\Order\OrderPricing\AdvancedPriceCalculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class ApiPricingConvertController
{
    public function __construct(
        private readonly AdvancedPriceCalculator $calc,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function __invoke(Request $request): PricingConvertOutput
    {
        $data = json_decode($request->getContent() ?: '{}', true) ?? [];

        $input = new PricingConvertInput();
        $input->items = (array) ($data['items'] ?? []);
        $input->displayCurrency = (string) ($data['displayCurrency'] ?? 'USD');
        $input->discountAmountMinor = isset($data['discountAmountMinor']) ? (int) $data['discountAmountMinor'] : null;
        $input->discountPercent = isset($data['discountPercent']) ? (float) $data['discountPercent'] : null;
        $input->taxMode = (string) ($data['taxMode'] ?? 'flat');
        $input->taxRate = (float) ($data['taxRate'] ?? 0.0);
        $input->brackets = (array) ($data['brackets'] ?? []);
        $input->taxAfterDiscount = (bool) ($data['taxAfterDiscount'] ?? true);

        $this->validator->validate($input);

        $subtotalMinor = $this->resolveSubtotalMinor($input->items);
        $discountMinor = $this->resolveDiscountMinor($subtotalMinor, $input->discountAmountMinor, $input->discountPercent);

        $breakdown = $this->calc->calculate(
            $this->minorToDecimal($subtotalMinor),
            $this->minorToDecimal($discountMinor),
            (string) $input->taxRate,
        );

        return new PricingConvertOutput(
            $this->decimalToMinor((string) $breakdown['subtotal']),
            $this->decimalToMinor((string) $breakdown['discount']),
            $this->decimalToMinor((string) $breakdown['tax']),
            $this->decimalToMinor((string) $breakdown['total']),
            $input->displayCurrency,
        );
    }

    /** @param array<int, array<string, mixed>> $items */
    private function resolveSubtotalMinor(array $items): int
    {
        $subtotalMinor = 0;
        foreach ($items as $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            $priceMinor = (int) ($item['unitPriceMinor'] ?? $item['priceMinor'] ?? 0);
            $subtotalMinor += $qty * $priceMinor;
        }

        return $subtotalMinor;
    }

    private function resolveDiscountMinor(int $subtotalMinor, ?int $discountAmountMinor, ?float $discountPercent): int
    {
        if (null !== $discountAmountMinor) {
            return max(0, $discountAmountMinor);
        }

        if (null !== $discountPercent && $discountPercent > 0) {
            return (int) round($subtotalMinor * ($discountPercent / 100));
        }

        return 0;
    }

    private function minorToDecimal(int $minor): string
    {
        return number_format($minor / 100, 2, '.', '');
    }

    private function decimalToMinor(string $amount): int
    {
        return (int) round(((float) $amount) * 100);
    }
}
