<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Service\Payment\Order;

use App\Ordering\ServiceInterface\Payment\Order\PaymentServiceInterface;

final class PaymentService implements PaymentServiceInterface
{
    public function applyPayment(string $orderId, string $amount, string $txId): void
    {
        // РўСѓС‚ РїСЂРёРІСЏР·РєР° Рє WriteModel + РїРµСЂРµСЂР°СЃС‡С‘С‚ paid_total (РѕРїСѓС‰РµРЅРѕ РґР»СЏ РєСЂР°С‚РєРѕСЃС‚Рё)
        // РЎРѕР±С‹С‚РёРµ РѕС‚РїСЂР°РІР»СЏРµС‚СЃСЏ С‡РµСЂРµР· TransactionalEventPublisher РІ С…РµРЅРґР»РµСЂРµ
    }
}
