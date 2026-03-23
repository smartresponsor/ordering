<?php

declare(strict_types=1);

namespace App\Service\Order;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;

#[AsEventListener(event: 'kernel.request', priority: 8)]
final class OrderApiRateLimitListener
{
    public function __construct(private RateLimiterFactory $orderApiLimiter)
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!str_starts_with($request->getPathInfo(), '/api/orders')) {
            return;
        }

        $key = $request->getClientIp() ?? 'anon';
        $limit = $this->orderApiLimiter->create($key)->consume(1);

        if (false === $limit->isAccepted()) {
            throw new TooManyRequestsHttpException(60, 'Rate limit exceeded for /api/orders');
        }
    }
}
