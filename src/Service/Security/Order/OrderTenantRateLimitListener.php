<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use App\RateLimiter\TenantKeyResolver;
use App\ServiceInterface\Security\Order\OrderTenantRateLimitListenerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;

#[AsEventListener(event: 'kernel.request', priority: 9)]
final readonly class OrderTenantRateLimitListener implements OrderTenantRateLimitListenerInterface
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
        $limit = $this->orderApiTenantLimiter->create($key)->consume();

        if (!$limit->isAccepted()) {
            throw new TooManyRequestsHttpException(60, 'Tenant rate limit exceeded');
        }
    }
}
