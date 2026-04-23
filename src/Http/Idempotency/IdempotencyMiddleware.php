<?php

declare(strict_types=1);

namespace App\Http\Idempotency;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final readonly class IdempotencyMiddleware implements HttpKernelInterface
{
    public function __construct(
        private HttpKernelInterface $kernel,
        private CacheItemPoolInterface $cache,
        private int $ttl = 60,
    ) {
    }

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        $key = $request->headers->get('Idempotency-Key') ?? '';
        if ('' !== $key) {
            $item = $this->cache->getItem($key);
            $cached = $item->isHit() ? $item->get() : null;
            if (is_array($cached) && isset($cached['content'], $cached['status'], $cached['headers']) && is_array($cached['headers'])) {
                return new Response((string) $cached['content'], (int) $cached['status'], $cached['headers']);
            }
        }

        $response = $this->kernel->handle($request, $type, $catch);
        if ('' !== $key) {
            $item = $this->cache->getItem($key);
            $item->set([
                'content' => (string) $response->getContent(),
                'status' => $response->getStatusCode(),
                'headers' => $response->headers->allPreserveCaseWithoutCookies(),
            ]);
            $item->expiresAfter($this->ttl);
            $this->cache->save($item);
        }

        return $response;
    }
}
