<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\Api\OrderPayInput;
use App\Entity\Order\OrderEntity;
use App\Repository\Order\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/orders/{id}/pay', name: 'api_orders_pay', methods: ['POST'])]
final readonly class OrderPayAction
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
        $dto = new OrderPayInput($data['amount'] ?? '0.00', $data['externalRef'] ?? 'api');
        $err = $this->validator->validate($dto);
        if (count($err) > 0) {
            return new JsonResponse(['errors' => (string) $err], 422);
        }
        try {
            $o->applyPartialPayment($dto->amount, $dto->externalRef);
            $this->em->flush();
        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        return new JsonResponse(['id' => $o->slug(), 'slug' => $o->slug(), 'status' => $o->getStatus(), 'paidTotal' => $o->getPaidTotal()]);
    }
}
