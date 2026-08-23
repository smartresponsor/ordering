<?php

declare(strict_types=1);

namespace App\Ordering\State\Api;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Ordering\DTO\Api\OrderInput;
use App\Ordering\DTO\Api\OrderOutput;
use App\Ordering\Entity\Order\OrderEntity;
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
