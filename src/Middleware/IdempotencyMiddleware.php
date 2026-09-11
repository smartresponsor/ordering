<?php

declare(strict_types=1);

namespace App\Ordering\Middleware;

final readonly class IdempotencyMiddleware extends Messenger\OrderMessageIdempotencyMiddleware
{
}
