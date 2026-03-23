<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ApiAuditSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $orderAuditLogger,
        private readonly TokenStorageInterface $tokens,
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

        $request = $event->getRequest();
        $response = $event->getResponse();
        $token = $this->tokens->getToken();
        $user = $token?->getUser();
        $userId = is_object($user) && method_exists($user, 'getUserIdentifier') ? (string) $user->getUserIdentifier() : 'anon';

        $this->orderAuditLogger->info('api', [
            'ip' => $request->getClientIp(),
            'user' => $userId,
            'method' => $request->getMethod(),
            'uri' => $request->getRequestUri(),
            'status' => $response->getStatusCode(),
        ]);
    }
}
