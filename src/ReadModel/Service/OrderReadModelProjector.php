<?php

declare(strict_types=1);

namespace App\Ordering\ReadModel\Service;

use App\Ordering\ReadModel\Entity\OrderViewEntity;
use Doctrine\ORM\EntityManagerInterface;

final readonly class OrderReadModelProjector
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @return array{id:string,status:string,grandTotal:string,paidTotal:string,refundedTotal:string}
     */
    public function project(string $orderId, ?string $status = null): array
    {
        /** @var OrderViewEntity|null $view */
        $view = $this->entityManager->getRepository(OrderViewEntity::class)->find($orderId);

        if (!$view instanceof OrderViewEntity) {
            $view = new OrderViewEntity($orderId);
            $this->entityManager->persist($view);
        }

        if (null !== $status && '' !== $status) {
            $view->setStatus($status);
        }

        return [
            'id' => $view->getId(),
            'status' => $view->getStatus(),
            'grandTotal' => $view->getGrandTotal(),
            'paidTotal' => $view->getPaidTotal(),
            'refundedTotal' => $view->getRefundedTotal(),
        ];
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
