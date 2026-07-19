<?php

declare(strict_types=1);

namespace App\Ordering\Tests\Unit\Order;

use App\Objecting\Embeddable\ObjectIdentityEmbeddable;
use App\Ordering\Entity\Order\OrderStatusEntity;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

final class OrderingObjectIdentityContractTest extends TestCase
{
    public function testOrderStatusUsesCanonicalObjectIdentityContract(): void
    {
        $status = new OrderStatusEntity('paid', 'Paid');
        $objectUuid = $status->getObjectUuid();

        self::assertSame(26, \strlen($objectUuid));
        self::assertInstanceOf(UuidV7::class, Uuid::fromString($objectUuid));
        self::assertMatchesRegularExpression('/^[0-9A-HJKMNP-TV-Z]{26}$/', $objectUuid);
        self::assertSame('paid', $status->getObjectSlug());

        $status->setObjectSlug('order-status-paid');

        self::assertSame($objectUuid, $status->getObjectUuid());
        self::assertSame('order-status-paid', $status->getObjectSlug());
    }

    public function testOrderStatusMappingUsesBinaryUuidMandatorySlugAndSeparatePrimaryKey(): void
    {
        $configuration = ORMSetup::createAttributeMetadataConfiguration([], true);
        $configuration->enableNativeLazyObjects(true);
        $driverChain = new MappingDriverChain();

        $orderingDriver = ORMSetup::createAttributeMetadataConfiguration([
            dirname(__DIR__, 3).'/src/Entity',
        ], true)->getMetadataDriverImpl();
        self::assertNotNull($orderingDriver);
        $driverChain->addDriver($orderingDriver, 'App\\Ordering\\Entity');

        $objectingDriver = ORMSetup::createAttributeMetadataConfiguration([
            dirname(__DIR__, 4).'/Objecting/src/Embeddable',
        ], true)->getMetadataDriverImpl();
        self::assertNotNull($objectingDriver);
        $driverChain->addDriver($objectingDriver, 'App\\Objecting\\Embeddable');
        $configuration->setMetadataDriverImpl($driverChain);

        $entityManager = new EntityManager(DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ], $configuration), $configuration);

        $metadata = $entityManager->getClassMetadata(OrderStatusEntity::class);
        $objectUuid = $metadata->getFieldMapping('objectIdentity.objectUuid');
        $objectSlug = $metadata->getFieldMapping('objectIdentity.objectSlug');

        self::assertSame(['id'], $metadata->getIdentifierFieldNames());
        self::assertSame('binary', $objectUuid['type']);
        self::assertSame(16, $objectUuid['length']);
        self::assertFalse($objectUuid['nullable'] ?? false);
        self::assertSame('string', $objectSlug['type']);
        self::assertSame(190, $objectSlug['length']);
        self::assertFalse($objectSlug['nullable'] ?? false);
        self::assertTrue($entityManager->getClassMetadata(ObjectIdentityEmbeddable::class)->isEmbeddedClass);
    }
}
