<?php

declare(strict_types=1);

namespace App\Ordering\Provider\Api;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderMetricsRollupProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $vendorId = (string) ($uriVariables['vendorId'] ?? '');

        return $this->em->getRepository(OrderMetricsRollupView::class)->findBy(['vendorId' => $vendorId]);
    }
}
