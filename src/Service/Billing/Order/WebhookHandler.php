<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Billing\Order;

use App\Entity\Order\Billing\OrderPaymentIntent;
use App\Entity\Order\Billing\OrderTransaction;
use App\ServiceInterface\Security\Order\OrderIdempotencyGuardInterface;
use App\ServiceInterface\Webhook\Order\OrderWebhookHandlerInterface;
use App\ServiceInterface\Webhook\Order\WebhookHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final readonly class WebhookHandler implements WebhookHandlerInterface, OrderWebhookHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private OrderIdempotencyGuardInterface $guard,
    ) {
    }

    public function handlePayment(Request $request): array
    {
        $provider = (string) $request->headers->get('X-Provider', 'mock');
        $eventId = (string) $request->headers->get('X-Event-Id', bin2hex(random_bytes(6)));
        $payload = $request->getContent() ?: '{}';

        if (!$this->guard->checkAndPersist($provider, $eventId, $payload)) {
            return ['status' => 'ignored', 'reason' => 'duplicate'];
        }

        $data = json_decode($payload, true);
        if (!is_array($data) || empty($data['intent']) || empty($data['status'])) {
            throw new BadRequestHttpException('Invalid payload');
        }

        $intentRepo = $this->em->getRepository(OrderPaymentIntent::class);
        $intent = $intentRepo->findOneBy(['intentId' => $data['intent']]);
        if (!$intent) {
            throw new BadRequestHttpException('Unknown payment intent');
        }

        // Update intent and create transaction
        if ('succeeded' === $data['status']) {
            $intent->markConfirmed();
            $txn = new OrderTransaction($intent->getOrderId(), $intent->getAmount(), (string) ($data['currency'] ?? $intent->getCurrency()), 'tx_'.bin2hex(random_bytes(8)));
            $txn->confirm();
            $this->em->persist($txn);
        } else {
            $intent->markFailed();
        }
        $this->em->flush();

        return ['status' => 'ok'];
    }

    public function handleRefund(Request $request): array
    {
        // For brevity: re-use handlePayment shape
        return $this->handlePayment($request);
    }
}
