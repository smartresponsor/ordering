<?php

declare(strict_types=1);

namespace App\Ordering\OpenApi;

use ApiPlatform\OpenApi\OpenApi;
use App\Ordering\ServiceInterface\OpenApi\Order\OrderOpenApiFactoryInterface;

final readonly class OrderOpenApiFactory implements OrderOpenApiFactoryInterface
{
    public function __construct(private object $inner)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        /** @var OpenApi $openApi */
        $openApi = $this->inner;

        return $openApi;
    }
}
