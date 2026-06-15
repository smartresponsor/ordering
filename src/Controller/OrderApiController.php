<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\OrderCreateDTO;
use App\DTO\OrderPaymentDTO;
use App\DTO\OrderRefundDTO;
use App\DTO\OrderShipmentDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final readonly class OrderApiController
{
    #[Route('/orders', name: 'orders_collection', methods: ['GET'])]
    public function collection(): JsonResponse
    {
        return new JsonResponse(['items' => [], 'status' => 'ok']);
    }

    #[Route('/orders', name: 'orders_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        $data = is_array($payload) ? $payload : [];
        $dto = new OrderCreateDTO();
        $dto->currency = (string) ($data['currency'] ?? 'USD');
        $dto->grandTotal = (string) ($data['grandTotal'] ?? '0.00');

        return new JsonResponse([
            'id' => 'api_'.bin2hex(random_bytes(6)),
            'status' => 'created',
            'currency' => $dto->currency,
            'grandTotal' => $dto->grandTotal,
        ], 201);
    }

    #[Route('/orders/{id}', name: 'orders_get', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        return new JsonResponse([
            'id' => $id,
            'status' => 'draft',
            'grandTotal' => '0.00',
            'paidTotal' => '0.00',
            'refundedTotal' => '0.00',
            'currency' => 'USD',
        ]);
    }

    #[Route('/api/order/pay/{id}', name: 'api_order_pay', methods: ['POST'])]
    #[Route('/orders/{id}/pay', name: 'orders_pay', methods: ['POST'])]
    public function pay(string $id, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        $data = is_array($payload) ? $payload : [];
        $dto = new OrderPaymentDTO();
        $dto->amount = (string) ($data['amount'] ?? '0.00');
        $dto->externalRef = (string) ($data['externalRef'] ?? 'unknown');

        return new JsonResponse(['id' => $id, 'status' => 'paid', 'paidTotal' => $dto->amount], 202);
    }

    #[Route('/api/order/ship/{id}', name: 'api_order_ship', methods: ['POST'])]
    #[Route('/orders/{id}/ship', name: 'orders_ship', methods: ['POST'])]
    public function ship(string $id, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        $data = is_array($payload) ? $payload : [];
        $dto = new OrderShipmentDTO();
        $dto->carrier = (string) ($data['carrier'] ?? 'UPS');
        $dto->note = isset($data['note']) ? (string) $data['note'] : null;

        return new JsonResponse(['id' => $id, 'status' => 'shipped', 'carrier' => $dto->carrier], 202);
    }

    #[Route('/api/order/refund/{id}', name: 'api_order_refund', methods: ['POST'])]
    #[Route('/orders/{id}/refund', name: 'orders_refund', methods: ['POST'])]
    public function refund(string $id, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        $data = is_array($payload) ? $payload : [];
        $dto = new OrderRefundDTO();
        $dto->amount = (string) ($data['amount'] ?? '0.00');
        $dto->reason = isset($data['reason']) ? (string) $data['reason'] : null;

        return new JsonResponse(['id' => $id, 'status' => 'refunded', 'refundedTotal' => $dto->amount], 202);
    }

    #[Route('/orders/{id}/complete', name: 'orders_complete', methods: ['POST'])]
    public function complete(string $id): JsonResponse
    {
        return new JsonResponse(['id' => $id, 'status' => 'completed'], 200);
    }

    #[Route('/api/order/invoice/{id}', name: 'api_order_invoice', methods: ['POST'])]
    #[Route('/orders/{id}/invoice', name: 'orders_invoice', methods: ['POST'])]
    public function invoice(string $id): JsonResponse
    {
        return new JsonResponse(['id' => $id, 'invoiceId' => 'inv_'.bin2hex(random_bytes(4))], 200);
    }

    #[Route('/api/order/payment/{id}', name: 'api_order_payments', methods: ['POST'])]
    #[Route('/orders/{id}/payments', name: 'orders_payments', methods: ['POST'])]
    public function payments(string $id): JsonResponse
    {
        return new JsonResponse(['id' => $id, 'intentId' => 'pi_test_'.bin2hex(random_bytes(4))], 200);
    }
}
