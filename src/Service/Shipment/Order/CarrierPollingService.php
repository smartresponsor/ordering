<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Shipment\Order;

use App\Integration\Shipment\CarrierInterface;
use App\RepositoryInterface\Order\OrderShipmentViewRepositoryInterface;
use App\ServiceInterface\Shipment\Order\CarrierPollingServiceInterface;
use App\ServiceInterface\Shipment\Order\OrderShipmentProjectionServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class CarrierPollingService implements CarrierPollingServiceInterface
{
    /** @var array<string, CarrierInterface> */
    private array $carriers = [];

    public function __construct(
        #[TaggedIterator('order.shipment.carrier')] iterable $carriers,
        private readonly OrderShipmentProjectionServiceInterface $projection,
        private readonly OrderShipmentViewRepositoryInterface $repo,
        private readonly LoggerInterface $logger,
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

        $normalizedStatus = strtolower($update->status);
        $existing = $this->repo->find($orderId);
        if (null !== $existing
            && $existing->carrier() === $carrier->name()
            && $existing->tracking() === $trackingNumber
            && $existing->status() === $normalizedStatus
            && $existing->deliveredAt()?->format(DATE_ATOM) === $update->deliveredAt?->format(DATE_ATOM)) {
            $this->logger->debug('Carrier polling produced no shipment changes', ['carrier' => $carrier->name(), 'orderId' => $orderId, 'status' => $normalizedStatus]);

            return true;
        }

        $this->projection->updateFromExternal($orderId, $carrier->name(), $trackingNumber, $normalizedStatus, $update->deliveredAt);
        $this->logger->info('Carrier polled', ['carrier' => $carrier->name(), 'orderId' => $orderId, 'status' => $normalizedStatus]);

        return true;
    }
}
