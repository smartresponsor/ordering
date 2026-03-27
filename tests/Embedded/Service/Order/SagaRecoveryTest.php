<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Message\Command\FlakyCommand;
use App\MessageHandler\FlakyHandler;
use PHPUnit\Framework\TestCase;

final class SagaRecoveryTest extends TestCase
{
    public function testFlakyHandlerRecoversOnRetry(): void
    {
        $handler = new FlakyHandler();
        try {
            $handler(new FlakyCommand('x1'));
            $this->fail('Should fail first time');
        } catch (\RuntimeException $e) {
            self::assertNotEmpty($e->getMessage());
        }

        // second call should pass
        $handler(new FlakyCommand('x1'));
        $this->assertTrue(true);
    }
}
