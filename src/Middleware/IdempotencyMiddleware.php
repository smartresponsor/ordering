<?php

declare(strict_types=1);

namespace App\Ordering\Middleware;

final class IdempotencyMiddleware extends Messenger\OrderMessageIdempotencyMiddleware
{
}
