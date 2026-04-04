<?php

declare(strict_types=1);

namespace App\Api\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\Analytics\Order\OrderMetricsView;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderMetricsProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        $vendorId = (string) ($uriVariables['vendorId'] ?? '');

        return $this->em->getRepository(OrderMetricsView::class)->findOneBy(['vendorId' => $vendorId]);
    }
}
