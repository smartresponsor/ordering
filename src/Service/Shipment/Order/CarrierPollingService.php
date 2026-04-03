<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Shipment\Order;

use App\Integration\Shipment\CarrierInterface;
use App\Repository\Order\OrderShipmentViewRepository;
use App\ServiceInterface\Shipment\Order\CarrierPollingServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class CarrierPollingService implements CarrierPollingServiceInterface
{
    /** @var array<string, CarrierInterface> */
    private array $carriers = [];

    public function __construct(
        #[TaggedIterator('order.shipment.carrier')] iterable $carriers,
        private OrderShipmentProjectionService $projection,
        private OrderShipmentViewRepository $repo,
        private LoggerInterface $logger,
    ) {
        foreach ($carriers as $carrier) {
            $this->carriers[strtolower($carrier->name())] = $carrier;
        }
    }

    public function poll(string $carrierName, string $orderId, string $trackingNumber): bool
    {
        $key = strtolower($carrierName);
        if (!isset($this->carriers[$key])) {
            $this->logger->warning('CarrierPollingService: unknown carrier', ['carrier' => $carrierName]);

            return false;
        }
        $carrier = $this->carriers[$key];
        $update = $carrier->fetchStatus($trackingNumber);
        if (null === $update) {
            return false;
        }
        $this->projection->updateFromExternal($orderId, $carrier->name(), $trackingNumber, $update->status, $update->deliveredAt);
        $this->logger->info('Carrier polled', ['carrier' => $carrier->name(), 'orderId' => $orderId, 'status' => $update->status]);

        return true;
    }
}
