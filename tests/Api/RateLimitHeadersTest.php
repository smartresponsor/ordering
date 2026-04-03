<?php

declare(strict_types=1);

namespace Tests\Api;

use App\Http\Middleware\RateLimitHeaderMiddleware;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\InMemoryStorage;

final class RateLimitHeadersTest extends TestCase
{
    public function testHeadersPresent(): void
    {
        $kernel = new class implements \Symfony\Component\HttpKernel\HttpKernelInterface {
            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                return new Response('ok', 200);
            }
        };

        $limiterFactory = new RateLimiterFactory([
            'id' => 'api_test',
            'policy' => 'fixed_window',
            'limit' => 100,
            'interval' => '60 seconds',
        ], new InMemoryStorage());

        $mw = new RateLimitHeaderMiddleware($kernel, $limiterFactory);
        $resp = $mw->handle(Request::create('/api/orders', 'GET'));
        $this->assertTrue($resp->headers->has('X-RateLimit-Limit'));
        $this->assertTrue($resp->headers->has('X-RateLimit-Remaining'));
        $this->assertTrue($resp->headers->has('X-RateLimit-Reset'));
    }
}
