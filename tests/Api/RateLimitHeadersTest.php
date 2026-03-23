<?php

declare(strict_types=1);

namespace Tests\Api;

use App\Http\Middleware\RateLimitHeaderMiddleware;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        $limiterFactory = new class implements \Symfony\Component\RateLimiter\RateLimiterFactory {
            public function create(string $id): \Symfony\Component\RateLimiter\LimiterInterface
            {
                return new class implements \Symfony\Component\RateLimiter\LimiterInterface {
                    private int $limit = 100;
                    private int $remaining = 99;

                    public function consume(int $tokens = 1): \Symfony\Component\RateLimiter\RateLimit
                    {
                        $this->remaining = max(0, $this->remaining - $tokens);

                        return new \Symfony\Component\RateLimiter\RateLimit(
                            $this->remaining,
                            new \DateTimeImmutable('+60 seconds'),
                            $this->limit
                        );
                    }

                    public function reset(): void
                    {
                    }
                };
            }
        };

        $mw = new RateLimitHeaderMiddleware($kernel, $limiterFactory);
        $resp = $mw->handle(Request::create('/api/orders', 'GET'));
        $this->assertTrue($resp->headers->has('X-RateLimit-Limit'));
        $this->assertTrue($resp->headers->has('X-RateLimit-Remaining'));
        $this->assertTrue($resp->headers->has('X-RateLimit-Reset'));
    }
}
