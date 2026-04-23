<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251015Core extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $orders = $schema->createTable('orders');
        $orders->addColumn('id', 'integer', ['autoincrement' => true]);
        $orders->addColumn('number', 'string', ['length' => 64]);
        $orders->setPrimaryKey(['id']);
        $orders->addUniqueIndex(['number']);
        $items = $schema->createTable('order_items');
        $items->addColumn('id', 'integer', ['autoincrement' => true]);
        $items->addColumn('order_id', 'integer');
        $items->addColumn('sku', 'string', ['length' => 64]);
        $items->addColumn('quantity', 'integer');
        $items->setPrimaryKey(['id']);
        $items->addForeignKeyConstraint('orders', ['order_id'], ['id']);
        $reservations = $schema->createTable('reservations');
        $reservations->addColumn('id', 'integer', ['autoincrement' => true]);
        $reservations->addColumn('order_item_id', 'integer');
        $reservations->addColumn('qty', 'integer');
        $reservations->setPrimaryKey(['id']);
        $reservations->addForeignKeyConstraint('order_items', ['order_item_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('reservations');
        $schema->dropTable('order_items');
        $schema->dropTable('orders');
    }
}
