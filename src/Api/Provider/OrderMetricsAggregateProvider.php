<?php

declare(strict_types=1);

namespace App\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\Order\OrderMetricsAggregateView;
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
