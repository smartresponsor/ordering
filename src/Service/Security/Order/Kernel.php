<?php

declare(strict_types=1);

namespace App\Ordering\Service\Security\Order;

use App\Interfacing\InterfacingBundle;
use App\Viewing\ViewingBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new SecurityBundle(),
            new TwigBundle(),
            new InterfacingBundle(),
            new ViewingBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $projectDir = \dirname(__DIR__, 4);
        $loader->load($projectDir.'/config/packages/framework.yaml');
        $loader->load($projectDir.'/config/packages/doctrine.yaml');
        $loader->load($projectDir.'/config/packages/security.yaml');
        $loader->load($projectDir.'/config/packages/twig.yaml');
        $loader->load($projectDir.'/config/services.standalone.yaml');
    }
}
