<?php

declare(strict_types=1);

namespace Tests\Api;

use App\Http\Idempotency\IdempotencyMiddleware;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
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

        $cache = new ArrayAdapter();

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
