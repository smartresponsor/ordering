<?php

declare(strict_types=1);

namespace App\Ordering\Repository\Order;

use App\Ordering\Entity\Order\OrderPaymentTranslationEntity;
use App\Ordering\RepositoryInterface\Order\OrderPaymentTranslationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderPaymentTranslationRepository extends ServiceEntityRepository implements OrderPaymentTranslationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPaymentTranslationEntity::class);
    }
}
