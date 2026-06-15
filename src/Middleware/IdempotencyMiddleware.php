<?php

declare(strict_types=1);

namespace App\Middleware;

final class IdempotencyMiddleware extends Messenger\OrderMessageIdempotencyMiddleware
{
}
