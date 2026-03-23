<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Controller\Order;

use App\Api\Order\Dto\OrderCreateInput;
use App\Entity\Order\Order;
use App\Entity\Order\OrderItem;
use App\ValueObject\Order\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class OrderCreateController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $dto = new OrderCreateInput();
        $dto->vendorId = (string) ($data['vendorId'] ?? '');
        $dto->currency = (string) ($data['currency'] ?? 'USD');
        $dto->items = $data['items'] ?? [];

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 422);
        }

        $order = new Order($dto->vendorId, new Money('0.00', $dto->currency));
        $this->em->persist($order);
        foreach ($dto->items as $line) {
            $item = new OrderItem($order, (string) $line['price'], $dto->currency);
            $this->em->persist($item);
        }
        $this->em->flush();

        return new JsonResponse(['id' => $order->getId()], 201);
    }
}
