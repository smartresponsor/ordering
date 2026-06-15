<?php

declare(strict_types=1);

namespace App\Provider\Api;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderMetricsAggregateProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $vendorId = (string) ($uriVariables['vendorId'] ?? '');

        return $this->em->getRepository(OrderMetricsAggregateView::class)->findBy(['vendorId' => $vendorId]);
    }
}
