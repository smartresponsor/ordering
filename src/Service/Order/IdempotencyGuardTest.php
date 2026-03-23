<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Service\Order\Billing\IdempotencyGuard;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class IdempotencyGuardTest extends KernelTestCase
{
    /**
     * @throws \JsonException
     */
    public function testCheckAndPersistBehaviour(): void
    {
        self::bootKernel();
        $guard = static::getContainer()->get(IdempotencyGuard::class);

        $payload = json_encode(['a' => 1], JSON_THROW_ON_ERROR);
        $ok1 = $guard->checkAndPersist('mock', 'evt_1', $payload);
        $ok2 = $guard->checkAndPersist('mock', 'evt_1', $payload); // duplicate
        self::assertTrue($ok1);
        self::assertFalse($ok2);
    }
}
