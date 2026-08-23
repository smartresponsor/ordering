<?php

declare(strict_types=1);

namespace Tests\Embedded\Service\Order;

use App\Ordering\Service\SensitiveData\MaskingService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class MaskingServiceTest extends KernelTestCase
{
    public function testMaskingAppliesOnNestedArrays(): void
    {
        self::bootKernel();
        /** @var MaskingService $svc */
        $svc = self::$kernel->getContainer()->get(MaskingService::class);
        $input = ['email' => 'user@example.com', 'nested' => ['card_number' => '4111111111111111', 'keep' => 'ok']];
        $out = $svc->maskArray($input);
        self::assertNotSame($input, $out);
        self::assertNotSame('user@example.com', $out['email']);
        self::assertNotSame('4111111111111111', $out['nested']['card_number']);
        self::assertSame('ok', $out['nested']['keep']);
    }
}
