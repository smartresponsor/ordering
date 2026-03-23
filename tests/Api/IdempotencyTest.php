<?php

declare(strict_types=1);

namespace Tests\Api;

use App\Http\Idempotency\IdempotencyMiddleware;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class IdempotencyTest extends TestCase
{
    public function testReplayReturnsCachedResponse(): void
    {
        $kernel = new class implements \Symfony\Component\HttpKernel\HttpKernelInterface {
            public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
            {
                return new Response('ok', 201, ['X-Fresh' => '1']);
            }
        };

        $cache = new class implements \Psr\SimpleCache\CacheInterface {
            private array $s = [];

            public function get($key, $default = null)
            {
                return $this->s[$key] ?? $default;
            }

            public function set($key, $value, $ttl = null)
            {
                $this->s[$key] = $value;

                return true;
            }

            public function has($key)
            {
                return array_key_exists($key, $this->s);
            }

            public function delete($key)
            {
                unset($this->s[$key]);

                return true;
            }

            public function clear()
            {
                $this->s = [];

                return true;
            }

            public function getMultiple($keys, $default = null)
            {
                return [];
            }

            public function setMultiple($values, $ttl = null)
            {
                return true;
            }

            public function deleteMultiple($keys)
            {
                return true;
            }
        };

        $mw = new IdempotencyMiddleware($kernel, $cache, 60);
        $r1 = Request::create('/api/orders', 'POST', [], [], [], ['HTTP_Idempotency-Key' => 'abc']);
        $resp1 = $mw->handle($r1);
        $this->assertSame(201, $resp1->getStatusCode());
        $this->assertSame('1', $resp1->headers->get('X-Fresh'));

        $r2 = Request::create('/api/orders', 'POST', [], [], [], ['HTTP_Idempotency-Key' => 'abc']);
        $resp2 = $mw->handle($r2);
        $this->assertSame(201, $resp2->getStatusCode());
        $this->assertNull($resp2->headers->get('X-Fresh')); // header not re-added, served from cache
        $this->assertSame('ok', $resp2->getContent());
    }
}
