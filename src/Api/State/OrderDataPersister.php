<?php

declare(strict_types=1);

namespace App\Api\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Dto\OrderInput;
use App\Api\Dto\OrderOutput;
use App\Entity\Order;
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

        $order = new Order();
        $this->em->persist($order);
        $this->em->flush();

        return OrderOutput::fromEntity($order);
    }
}
