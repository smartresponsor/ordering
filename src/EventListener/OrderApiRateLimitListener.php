<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;

final readonly class OrderApiRateLimitListener
{
    public function __construct(private RateLimiterFactory $orderApiLimiter)
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!str_starts_with($request->getPathInfo(), '/api/orders')) {
            return;
        }

        $key = $request->getClientIp() ?? 'anon';
        $limit = $this->orderApiLimiter->create($key)->consume();
        if (!$limit->isAccepted()) {
            throw new TooManyRequestsHttpException(null, 'Order API rate limit exceeded.');
        }
    }
}
