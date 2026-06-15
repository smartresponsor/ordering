<?php

declare(strict_types=1);

namespace App\State\Api;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\Api\OrderInput;
use App\DTO\Api\OrderOutput;
use App\Entity\Order\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderDataPersister implements ProcessorInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof OrderInput) {
            return $data;
        }

        $order = new OrderEntity();
        $this->em->persist($order);
        $this->em->flush();

        return OrderOutput::fromEntity($order);
    }
}
