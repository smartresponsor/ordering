<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\OrderCreateDTO;
use App\DTO\OrderPaymentDTO;
use App\DTO\OrderRefundDTO;
use App\DTO\OrderShipmentDTO;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/order')]
final readonly class OrderController
{
    public function __construct(private EntityManagerInterface $em, private ValidatorInterface $validator)
    {
    }

    #[Route('', name: 'order_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $dto = new OrderCreateDTO();
        $dto->currency = (string) ($data['currency'] ?? 'USD');
        $dto->grandTotal = (string) ($data['grandTotal'] ?? '0.00');
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 422);
        }
        $order = new Order($dto->currency, $dto->grandTotal);
        $this->em->persist($order);
        $this->em->flush();

        return new JsonResponse(['id' => $order->id(), 'status' => $order->status()], 201);
    }

    #[Route('/{id}', name: 'order_get', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        return new JsonResponse([
            'id' => $order->id(),
            'status' => $order->status(),
            'grandTotal' => $order->grandTotal(),
            'paidTotal' => $order->paidTotal(),
            'refundedTotal' => $order->refundedTotal(),
            'currency' => $order->currency(),
        ]);
    }

    #[Route('/health', name: 'order_health_legacy', methods: ['GET'])]
    public function health(): JsonResponse
    {
        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/lookup/{id}', name: 'order_get_legacy', methods: ['GET'])]
    public function getById(string $id): JsonResponse
    {
        return $this->get($id);
    }

    #[Route('/{id}/pay', name: 'order_pay', methods: ['POST'])]
    public function pay(string $id, Request $request): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $dto = new OrderPaymentDTO();
        $dto->amount = (string) ($data['amount'] ?? '0.00');
        $dto->externalRef = (string) ($data['externalRef'] ?? 'unknown');
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 422);
        }

        try {
            $payment = $order->applyPayment($dto->amount, $dto->externalRef);
            $this->em->persist($payment);
            $this->em->flush();
        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        return new JsonResponse(['id' => $order->id(), 'status' => $order->status(), 'paidTotal' => $order->paidTotal()]);
    }

    #[Route('/{id}/ship', name: 'order_ship', methods: ['POST'])]
    public function ship(string $id, Request $request): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $dto = new OrderShipmentDTO();
        $dto->carrier = (string) ($data['carrier'] ?? 'UPS');
        $dto->note = isset($data['note']) ? (string) $data['note'] : null;
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 422);
        }

        try {
            $shipment = $order->ship($dto->carrier, null, $dto->note);
            $this->em->persist($shipment);
            $this->em->flush();
        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        return new JsonResponse(['id' => $order->id(), 'status' => $order->status()]);
    }

    #[Route('/{id}/refund', name: 'order_refund', methods: ['POST'])]
    public function refund(string $id, Request $request): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        $data = json_decode($request->getContent(), true) ?? [];
        $dto = new OrderRefundDTO();
        $dto->amount = (string) ($data['amount'] ?? '0.00');
        $dto->reason = isset($data['reason']) ? (string) $data['reason'] : null;
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 422);
        }

        try {
            $refund = $order->refund($dto->amount, $dto->reason);
            $this->em->persist($refund);
            $this->em->flush();
        } catch (\DomainException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        return new JsonResponse(['id' => $order->id(), 'status' => $order->status(), 'refundedTotal' => $order->refundedTotal()]);
    }
}
