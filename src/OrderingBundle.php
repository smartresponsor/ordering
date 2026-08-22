<?php

declare(strict_types=1);

namespace App\Ordering;

use App\Ordering\DependencyInjection\OrderingExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class OrderingBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        $extension = parent::getContainerExtension();

        return $extension instanceof ExtensionInterface ? $extension : new OrderingExtension();
    }
}
