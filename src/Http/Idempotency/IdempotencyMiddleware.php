<?php

declare(strict_types=1);

namespace App\Http\Idempotency;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class IdempotencyMiddleware implements HttpKernelInterface
{
    public function __construct(
        private HttpKernelInterface $kernel,
        private CacheInterface $cache,
        private int $ttl = 60,
    ) {
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        $key = (string) ($request->headers->get('Idempotency-Key') ?? '');
        if ('' !== $key) {
            $cached = $this->cache->get($key);
            if (is_array($cached) && isset($cached['content'], $cached['status'], $cached['headers'])) {
                return new Response($cached['content'], (int) $cached['status'], $cached['headers']);
            }
        }

        $response = $this->kernel->handle($request, $type, $catch);
        if ('' !== $key) {
            $this->cache->set($key, [
                'content' => $response->getContent(),
                'status' => $response->getStatusCode(),
                'headers' => $response->headers->allPreserveCaseWithoutCookies(),
            ], $this->ttl);
        }

        return $response;
    }
}
