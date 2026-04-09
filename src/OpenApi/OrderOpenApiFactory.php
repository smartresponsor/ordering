<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\OpenApi\OpenApi;
use App\ServiceInterface\OpenApi\Order\OrderOpenApiFactoryInterface;

final readonly class OrderOpenApiFactory implements OrderOpenApiFactoryInterface
{
    public function __construct(private readonly object $inner)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        /** @var OpenApi $openApi */
        $openApi = $this->inner;

        return $openApi;
    }
}
