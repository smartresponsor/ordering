<?php

declare(strict_types=1);

namespace App\Ordering\Controller\Api;

use App\Ordering\DTO\Api\OrderRefundInput;
use App\Ordering\Entity\Order\OrderEntity;
use App\Ordering\Repository\Order\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/orders/{id}/refund', name: 'api_orders_refund', methods: ['POST'])]
final readonly class OrderRefundAction
{
    public function __construct(private EntityManagerInterface $em, private ValidatorInterface $validator)
    {
    }

    public function __invoke(string $id, Request $request): JsonResponse
    {
        /** @var OrderRepository $repo */
        $repo = $this->em->getRepository(OrderEntity::class);
        $o = $repo->findByIdentifier($id);
        if (!$o instanceof OrderEntity) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }
        $data = json_decode($request->getContent(), true) ?? [];
        $dto = new OrderRefundInput($data['amount'] ?? '0.00', $data['reason'] ?? null);
        $err = $this->validator->validate($dto);
        if (count($err) > 0) {
            return new JsonResponse(['errors' => (string) $err], 422);
        }
        try {
            $o->refundPartial($dto->amount, $dto->reason);
            $this->em->flush();
        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        return new JsonResponse(['id' => $o->slug(), 'slug' => $o->slug(), 'status' => $o->getStatus(), 'refundedTotal' => $o->getRefundedTotal()]);
    }
}
