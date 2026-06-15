<?php

declare(strict_types=1);

namespace App\Processor\Api;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

final class OrderPartialPaymentProcessor implements ProcessorInterface
{
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        return $data;
    }
}
