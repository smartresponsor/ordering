<?php

declare(strict_types=1);

namespace App\Service\Subscriber\Order;

use App\ServiceInterface\Subscriber\Order\ApiAuditSubscriberInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final readonly class ApiAuditSubscriber implements EventSubscriberInterface, ApiAuditSubscriberInterface
{
    public function __construct(
        private LoggerInterface $orderAuditLogger,
        private TokenStorageInterface $tokens,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::RESPONSE => 'onResponse'];
    }

    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $req = $event->getRequest();
        $res = $event->getResponse();
        $token = $this->tokens->getToken();
        $userId = $token?->getUser()?->getUserIdentifier() ?? 'anon';

        $this->orderAuditLogger->info('api', [
            'ip' => $req->getClientIp(),
            'user' => $userId,
            'method' => $req->getMethod(),
            'uri' => $req->getRequestUri(),
            'status' => $res->getStatusCode(),
        ]);
    }
}
