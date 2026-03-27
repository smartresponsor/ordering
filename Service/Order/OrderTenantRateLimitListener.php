<?php

declare(strict_types=1);

namespace App\Service\Order;

use App\RateLimiter\TenantKeyResolver;
use App\ServiceInterface\Order\OrderTenantRateLimitListenerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;

#[AsEventListener(event: 'kernel.request', priority: 9)]
final class OrderTenantRateLimitListener implements OrderTenantRateLimitListenerInterface
{
    public function __construct(
        private RateLimiterFactory $orderApiTenantLimiter,
        private TenantKeyResolver $keyResolver,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!str_starts_with($request->getPathInfo(), '/api/orders')) {
            return;
        }

        $key = $this->keyResolver->key();
        $limit = $this->orderApiTenantLimiter->create($key)->consume(1);

        if (!$limit->isAccepted()) {
            throw new TooManyRequestsHttpException(60, 'Tenant rate limit exceeded');
        }
    }
}
