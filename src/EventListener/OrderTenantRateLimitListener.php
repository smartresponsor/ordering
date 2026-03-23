<?php

declare(strict_types=1);

namespace App\EventListener;

use App\RateLimiter\TenantKeyResolver;
use Symfony\Component\HttpFoundation\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;

final readonly class OrderTenantRateLimitListener
{
    public function __construct(
        private RateLimiterFactory $orderApiTenantLimiter,
        private TenantKeyResolver $keyResolver,
    ) {
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

        $limit = $this->orderApiTenantLimiter->create($this->keyResolver->key())->consume(1);
        if (!$limit->isAccepted()) {
            throw new TooManyRequestsHttpException(null, 'Order tenant rate limit exceeded.');
        }
    }
}
