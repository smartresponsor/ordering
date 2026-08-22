<?php

declare(strict_types=1);

namespace App\Ordering\MessageHandler;

use App\Ordering\Message\Command\FlakyCommand;
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
