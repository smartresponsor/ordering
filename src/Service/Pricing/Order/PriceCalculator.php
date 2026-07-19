<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Entity\Order\OrderItemEntity;
use App\ServiceInterface\Pricing\Order\CurrencyConversionServiceInterface;
use App\ServiceInterface\Pricing\Order\DefaultPromotionStrategyInterface;
use App\ServiceInterface\Pricing\Order\PriceCalculatorInterface;
use App\ServiceInterface\Pricing\Order\TaxationConfigLoaderInterface;
use App\ServiceInterface\Pricing\Order\TaxationStrategyInterface;
use App\ValueObject\Pricing\Order\Currency;
use App\ValueObject\Pricing\Order\Money;
use App\ValueObject\Pricing\Order\Taxation;
use App\ValueObject\Pricing\Order\TaxRate;

readonly class PriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private DefaultPromotionStrategyInterface $promotions,
        private TaxationStrategyInterface $taxation,
        private TaxationConfigLoaderInterface $taxConfig,
        private CurrencyConversionServiceInterface $fx,
    ) {
    }

    /**
     * @param Money         $subtotal       money in order's currency
     * @param TaxRate       $rate           tax rate (from config)
     * @param Currency|null $targetCurrency convert final totals to this currency
     */
    /**
     * @return array{subtotal: Money, discount: Money, tax: Money, total: Money}
     */
    public function calculate(Money $subtotal, TaxRate $rate, ?Currency $targetCurrency = null): array
    {
        $discount = $this->promotions->discount($subtotal);
        $taxBase = $subtotal->subtract($discount);
        $taxation = new Taxation(((float) $rate->asDecimal()) / 100, 'vat');
        $tax = $this->taxation->compute($taxBase, $taxation);
        $total = $taxBase->add($tax);

        $scale = $this->taxConfig->rounding();
        $subtotal = $subtotal->round($scale);
        $discount = $discount->round($scale);
        $tax = $tax->round($scale);
        $total = $total->round($scale);

        if ($targetCurrency) {
            $subtotal = $this->fx->convert($subtotal, $targetCurrency->getCode());
            $discount = $this->fx->convert($discount, $targetCurrency->getCode());
            $tax = $this->fx->convert($tax, $targetCurrency->getCode());
            $total = $this->fx->convert($total, $targetCurrency->getCode());
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    /** @param OrderItemEntity[] $items */
    public function recalc(OrderEntity $order, array $items): void
    {
        $subtotal = Money::zero($order->getCurrency());
        $lineSubtotals = [];

        foreach ($items as $item) {
            if (!method_exists($item, 'subtotalMoney')) {
                continue;
            }

            /** @var Money $itemSubtotal */
            $itemSubtotal = $item->subtotalMoney();
            $subtotal = $subtotal->add($itemSubtotal);
            $lineSubtotals[] = $itemSubtotal;
        }

        $country = method_exists($order, 'getCountryCode') ? $order->getCountryCode() : null;
        $rate = $this->taxConfig->rateFor((string) ($country ?? 'US'));
        $result = $this->calculate($subtotal, $rate);
        $order->setSubtotal($result['subtotal']->getAmount());
        $order->setDiscountTotal($result['discount']->getAmount());
        $order->setTaxTotal($result['tax']->getAmount());
        $order->setGrandTotal($result['total']->getAmount());

        $this->allocateLineBreakdown($items, $lineSubtotals, $result);
    }

    /** @param OrderItemEntity[] $items
     * @param Money[]                                                           $lineSubtotals
     * @param array{subtotal: Money, discount: Money, tax: Money, total: Money} $result
     */
    private function allocateLineBreakdown(array $items, array $lineSubtotals, array $result): void
    {
        $subtotalMinor = $this->toMinor($result['subtotal']);
        $discountMinor = $this->toMinor($result['discount']);
        $taxMinor = $this->toMinor($result['tax']);

        $allocatedDiscount = 0;
        $allocatedTax = 0;
        $count = count($items);

        foreach ($items as $index => $item) {
            if (!method_exists($item, 'setPricingBreakdown')) {
                continue;
            }

            $lineMinor = $index < count($lineSubtotals) ? $this->toMinor($lineSubtotals[$index]) : 0;
            $itemDiscount = 0;
            if ($subtotalMinor > 0) {
                $itemDiscount = (int) round(($lineMinor / $subtotalMinor) * $discountMinor);
            }
            if ($index === $count - 1) {
                $itemDiscount = $discountMinor - $allocatedDiscount;
            }
            $allocatedDiscount += $itemDiscount;

            $lineTaxBase = max(0, $lineMinor - $itemDiscount);
            $taxBaseMinor = max(0, $subtotalMinor - $discountMinor);
            $itemTax = 0;
            if ($taxBaseMinor > 0) {
                $itemTax = (int) round(($lineTaxBase / $taxBaseMinor) * $taxMinor);
            }
            if ($index === $count - 1) {
                $itemTax = $taxMinor - $allocatedTax;
            }
            $allocatedTax += $itemTax;

            $itemFinal = max(0, $lineMinor - $itemDiscount + $itemTax);
            $item->setPricingBreakdown($itemDiscount, $itemTax, $itemFinal);
        }
    }

    private function toMinor(Money $money): int
    {
        return (int) round(((float) $money->getAmount()) * 100);
    }
}
