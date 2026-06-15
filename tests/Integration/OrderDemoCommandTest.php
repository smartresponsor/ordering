<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Entity\Order\OrderEntity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class OrderDemoCommandTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $tool = new SchemaTool($em);
        $tool->dropSchema($em->getMetadataFactory()->getAllMetadata());
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());
    }

    public function testDemoLoadAndReportCommands(): void
    {
        $application = new Application(self::$kernel);

        $loadTester = new CommandTester($application->find('app:order:demo:load'));
        self::assertSame(0, $loadTester->execute(['--count' => 5]));

        $em = self::getContainer()->get(EntityManagerInterface::class);
        self::assertCount(5, $em->getRepository(OrderEntity::class)->findAll());

        $reportTester = new CommandTester($application->find('app:order:report'));
        self::assertSame(0, $reportTester->execute([]));
        self::assertStringContainsString('Status', $reportTester->getDisplay());
    }
}
