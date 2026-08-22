<?php

declare(strict_types=1);

namespace App\Ordering\Service\Security\Order;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;

final readonly class OrderOpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        $paths = $openApi->getPaths();

        // Add example to POST /api/orders
        if ($pathItem = $paths->getPath('/api/orders')) {
            if ($operation = $pathItem->getPost()) {
                $requestBody = $operation->getRequestBody();
                if ($requestBody) {
                    $content = $requestBody->getContent();
                    if (isset($content['application/json'])) {
                        $schema = $content['application/json']->getSchema();
                        $schema = $schema ?? new Model\Schema('OrderCreate');
                        $content['application/json'] = $content['application/json']->withExample([
                            'vendorId' => 'V-123',
                            'currency' => 'USD',
                            'items' => [
                                ['sku' => 'SKU-1', 'price' => '10.00', 'qty' => 2],
                                ['sku' => 'SKU-2', 'price' => '5.50', 'qty' => 1],
                            ],
                        ]);
                        $requestBody = $requestBody->withContent($content);
                        $operation = $operation->withRequestBody($requestBody);
                        $pathItem = $pathItem->withPost($operation);
                        $paths->addPath('/api/orders', $pathItem);
                    }
                }
            }
        }

        return $openApi->withPaths($paths);
    }
}
