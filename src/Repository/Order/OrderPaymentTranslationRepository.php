<?php

declare(strict_types=1);

namespace App\Repository\Order;

use App\Entity\Order\OrderPaymentTranslationEntity;
use App\RepositoryInterface\Order\OrderPaymentTranslationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderPaymentTranslationRepository extends ServiceEntityRepository implements OrderPaymentTranslationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPaymentTranslationEntity::class);
    }
}
