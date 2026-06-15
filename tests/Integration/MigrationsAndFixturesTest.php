<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\DataFixtures\OrderFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

final class MigrationsAndFixturesTest extends TestCase
{
    private static KernelInterface $kernel;

    public static function setUpBeforeClass(): void
    {
        self::$kernel = new TestKernel('test', true);
        self::$kernel->boot();
    }

    public static function tearDownAfterClass(): void
    {
        self::$kernel->shutdown();
    }

    public function testMigrationsAndFixtures(): void
    {
        $container = self::$kernel->getContainer();
        $em = $container->get(EntityManagerInterface::class);

        // create schema fresh
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());

        // load fixtures
        (new OrderFixtures())->load($em);

        $count = (int) $em->createQuery('SELECT COUNT(o.id) FROM App\Entity\Order\OrderEntity o')->getSingleScalarResult();

        self::assertSame(4, $count);

        $slugs = $em->createQuery('SELECT o.slug FROM App\Entity\Order\OrderEntity o ORDER BY o.number ASC')->getScalarResult();
        self::assertCount(4, $slugs);
        foreach ($slugs as $row) {
            self::assertArrayHasKey('slug', $row);
            self::assertMatchesRegularExpression(
                '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
                $row['slug']
            );
            self::assertNotSame('7f2a4d8e-1c23-4f5d-8f90-2b3c4d5e1001', $row['slug']);
        }
    }
}
