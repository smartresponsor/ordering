<?php

declare(strict_types=1);

namespace App\Infrastructure\Monitoring;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

final readonly class HealthController implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    public function __construct(
        private Connection $db,
        private ?TransportInterface $orderTransport = null,
    ) {
    }

    #[Route('/_health/order', name: 'order_health_monitoring', methods: ['GET'])]
    public function liveness(): JsonResponse
    {
        try {
            $this->db->executeQuery('SELECT 1')->fetchOne();

            return new JsonResponse(['status' => 'ok', 'db' => true], 200);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'status' => 'fail',
                'db' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/_ready/order', name: 'order_ready_monitoring', methods: ['GET'])]
    public function readiness(): JsonResponse
    {
        $dbOk = false;
        $mqOk = true;
        $errors = [];

        try {
            $this->db->executeQuery('SELECT 1')->fetchOne();
            $dbOk = true;
        } catch (\Throwable $e) {
            $errors['db'] = $e->getMessage();
        }

        if (null !== $this->orderTransport) {
            try {
                $this->orderTransport->get();
            } catch (\Throwable $e) {
                $mqOk = false;
                $errors['mq'] = $e->getMessage();
            }
        }

        $ok = $dbOk && $mqOk;

        return new JsonResponse([
            'status' => $ok ? 'ok' : 'fail',
            'db' => $dbOk,
            'mq' => $mqOk,
            'errors' => $errors,
        ], $ok ? 200 : 503);
    }
}
