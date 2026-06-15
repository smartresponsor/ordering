<?php

declare(strict_types=1);

namespace App\Listener\Http\Order;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

#[AsEventListener(event: 'kernel.terminate')]
final readonly class OrderApiAuditTerminateListener
{
    public function __construct(private LoggerInterface $order_security)
    {
    }

    public function __invoke(TerminateEvent $event): void
    {
        $request = $event->getRequest();
        if (!str_starts_with($request->getPathInfo(), '/api/orders')) {
            return;
        }

        $response = $event->getResponse();
        $this->order_security->info('Order API call', [
            'path' => $request->getPathInfo(),
            'method' => $request->getMethod(),
            'status' => $response->getStatusCode(),
            'ip' => $request->getClientIp(),
        ]);
    }
}
