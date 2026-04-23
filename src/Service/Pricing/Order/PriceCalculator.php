<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Pricing\Order;

use App\Entity\Order;
use App\Entity\Order\OrderItem;
use App\ServiceInterface\Pricing\Order\CurrencyConversionServiceInterface;
use App\ServiceInterface\Pricing\Order\DefaultPromotionStrategyInterface;
use App\ServiceInterface\Pricing\Order\PriceCalculatorInterface;
use App\ServiceInterface\Pricing\Order\TaxationConfigLoaderInterface;
use App\ServiceInterface\Pricing\Order\TaxationStrategyInterface;
use App\ValueObject\Pricing\Order\Currency;
use App\ValueObject\Pricing\Order\Money;
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
    public function calculate(Money $subtotal, TaxRate $rate, ?Currency $targetCurrency = null): array
    {
        $discount = $this->promotions->discount($subtotal);
        $taxBase = $subtotal->subtract($discount);
        $tax = $this->taxation->compute($taxBase, $rate);
        $total = $taxBase->add($tax);

        $scale = $this->taxConfig->rounding();
        $subtotal = $subtotal->round($scale);
        $discount = $discount->round($scale);
        $tax = $tax->round($scale);
        $total = $total->round($scale);

        if ($targetCurrency) {
            $subtotal = $this->fx->convert($subtotal, $targetCurrency, $scale);
            $discount = $this->fx->convert($discount, $targetCurrency, $scale);
            $tax = $this->fx->convert($tax, $targetCurrency, $scale);
            $total = $this->fx->convert($total, $targetCurrency, $scale);
        }

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    /** @param OrderItem[] $items */
    public function recalc(Order $order, array $items): void
    {
        $subtotal = Money::zero($order->getCurrency());

        foreach ($items as $item) {
            if (!method_exists($item, 'subtotalMoney')) {
                continue;
            }

            /** @var Money $itemSubtotal */
            $itemSubtotal = $item->subtotalMoney();
            $subtotal = $subtotal->add($itemSubtotal);
        }

        $country = method_exists($order, 'getCountryCode') ? $order->getCountryCode() : null;
        $rate = $this->taxConfig->rateFor((string) ($country ?? 'US'));
        $result = $this->calculate($subtotal, $rate);
        $order->setSubtotal($result['subtotal']->getAmount());
        $order->setDiscountTotal($result['discount']->getAmount());
        $order->setTaxTotal($result['tax']->getAmount());
        $order->setGrandTotal($result['total']->getAmount());
    }
}
