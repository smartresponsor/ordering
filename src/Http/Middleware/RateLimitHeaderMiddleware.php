<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;

final class RateLimitHeaderMiddleware implements HttpKernelInterface
{
    public function __construct(
        private HttpKernelInterface $kernel,
        private RateLimiterFactory $limiterFactory,
    ) {
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        $limit = $this->limiterFactory->create((string) ($request->getClientIp() ?? 'anon'))->consume();
        $response = $this->kernel->handle($request, $type, $catch);
        $response->headers->set('X-RateLimit-Limit', (string) $limit->getLimit());
        $response->headers->set('X-RateLimit-Remaining', (string) $limit->getRemainingTokens());
        $response->headers->set('X-RateLimit-Reset', (string) $limit->getRetryAfter()->getTimestamp());

        return $response;
    }
}
