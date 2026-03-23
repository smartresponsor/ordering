<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 * Owner: Marketing America Corp.
 */

namespace App\Service\Order;

use App\Entity\Order\Billing\OrderPaymentIntent;
use App\Entity\Order\Billing\OrderTransaction;
use App\ServiceInterface\Order\WebhookHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class WebhookHandler implements WebhookHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly IdempotencyGuard $guard,
    ) {
    }

    public function handlePayment(Request $request): array
    {
        $provider = $request->headers->get('X-Provider', 'mock');
        $eventId = $request->headers->get('X-Event-Id', bin2hex(random_bytes(6)));
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
            $txn = new OrderTransaction($intent->getOrder(), 'tx_'.bin2hex(random_bytes(8)), $intent->getAmount(), $data['currency'] ?? 'USD');
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
