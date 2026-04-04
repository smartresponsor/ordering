<?php

declare(strict_types=1);

namespace App\Service\Security\Order;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $confDir = \dirname(__DIR__, 3).'/config/packages';
        $loader->load(function (ContainerBuilder $container) use ($confDir) {
            $yaml = new YamlFileLoader($container, new FileLocator($confDir));
            $yaml->load('framework.yaml');
            $yaml->load('doctrine.yaml');
            $container->setParameter('kernel.project_dir', \dirname(__DIR__, 3));
        });
    }
}
