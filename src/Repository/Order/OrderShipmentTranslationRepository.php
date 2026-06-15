<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderShipmentTranslationEntity;
use App\RepositoryInterface\Order\OrderShipmentTranslationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderShipmentTranslationRepository extends ServiceEntityRepository implements OrderShipmentTranslationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderShipmentTranslationEntity::class);
    }
}
