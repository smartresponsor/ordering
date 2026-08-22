<?php

declare(strict_types=1);

namespace App\Ordering\EventListener;

use App\Ordering\RateLimiter\TenantKeyResolver;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\RateLimiter\RateLimiterFactory;

#[AsEventListener(event: 'kernel.request', priority: 9)]
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

        $limit = $this->orderApiTenantLimiter->create($this->keyResolver->key())->consume();

        if (false === $limit->isAccepted()) {
            throw new TooManyRequestsHttpException(60, 'Order tenant rate limit exceeded.');
        }
    }
}
