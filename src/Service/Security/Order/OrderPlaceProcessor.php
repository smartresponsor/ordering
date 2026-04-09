<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Security\Order;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\Order\OrderResource;
use App\Message\Command\Order\OrderPlaceCommand;
use App\ServiceInterface\Security\Order\OrderPlaceProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @implements ProcessorInterface<OrderResource, OrderResource>
 */
final readonly class OrderPlaceProcessor implements ProcessorInterface, OrderPlaceProcessorInterface
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        \assert($data instanceof OrderResource);
        $orderId = Uuid::v7()->toRfc4122();

        $payload = [
            'orderId' => $orderId,
            'customerId' => $data->customerId,
            'vendorId' => $data->vendorId,
            'currency' => $data->currency ?? 'USD',
            'items' => array_map(
                static fn ($i) => ['sku' => $i->sku, 'qty' => $i->qty, 'price' => $i->price],
                $data->items,
            ),
            'placeAt' => $data->placeAt ?? (new \DateTimeImmutable())->format(DATE_ATOM),
        ];

        $this->bus->dispatch(new OrderPlaceCommand($payload));
        $this->em->flush(); // единая транзакция с outbox, если используется

        // Возвращаем облегчённый ресурс
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
