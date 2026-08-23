<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Ordering\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Ordering\ApiResource\View\Order\OrderResource;
use App\Ordering\Message\Command\Order\OrderPlaceCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @implements ProcessorInterface<OrderResource, OrderResource>
 */
final readonly class OrderPlaceProcessor implements ProcessorInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private EntityManagerInterface $em,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): OrderResource
    {
        \assert($data instanceof OrderResource);
        $orderId = Uuid::v7()->toRfc4122();

        $payload = [
            'orderId' => $orderId,
            'customerId' => $data->customerId,
            'vendorId' => $data->vendorId,
            'currency' => $data->currency ?? 'USD',
            'items' => array_map(
                static fn (array $item): array => ['sku' => $item['sku'], 'qty' => $item['qty'], 'price' => $item['price']],
                $data->items ?? [],
            ),
            'placeAt' => $data->placeAt ?? (new \DateTimeImmutable())->format(DATE_ATOM),
        ];

        $this->bus->dispatch(new OrderPlaceCommand($payload));
        $this->em->flush();

        return new OrderResource(
            id: $orderId,
            number: (string) $payload['orderId'],
            status: 'placed',
            currency: $payload['currency'],
            total: null,
            grandTotal: '0.00',
            paidTotal: '0.00',
            refundedTotal: '0.00',
            customerId: $payload['customerId'],
            vendorId: $payload['vendorId'],
            items: $payload['items'],
            placeAt: $payload['placeAt'],
        );
    }
}
