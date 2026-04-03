<?php

declare(strict_types=1);

namespace App\Service\Http\Order;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ProblemFactory
{
    /** @param array<string, mixed> $extra */
    public function create(string $title, string $detail, int $status = Response::HTTP_BAD_REQUEST, array $extra = []): JsonResponse
    {
        return new JsonResponse([
            'type' => 'about:blank',
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
        ] + $extra, $status);
    }
}
