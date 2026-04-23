<?php

declare(strict_types=1);

namespace App\Service\Subscriber\Order;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

#[AsEventListener(event: 'kernel.terminate')]
final readonly class OrderApiAuditSubscriber
{
    public function __construct(private LoggerInterface $order_security)
    {
    }

    public function __invoke(TerminateEvent $event): void
    {
        $req = $event->getRequest();
        if (!str_starts_with($req->getPathInfo(), '/api/orders')) {
            return;
        }
        $res = $event->getResponse();
        $this->order_security->info('Order API call', [
            'path' => $req->getPathInfo(),
            'method' => $req->getMethod(),
            'status' => $res->getStatusCode(),
            'ip' => $req->getClientIp(),
        ]);
    }
}
