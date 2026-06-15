<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\Command\FlakyCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async')]
class FlakyHandler
{
    private static array $seen = [];

    public function __invoke(FlakyCommand $cmd): void
    {
        if (!isset(self::$seen[$cmd->id])) {
            self::$seen[$cmd->id] = true;
            throw new \RuntimeException('Fail first time');
        }
    }
}
