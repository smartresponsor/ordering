<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add order status history, payment allocations and shipment items for doctrine-first order enrichment';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS order_status_history (id SERIAL NOT NULL, order_id VARCHAR(36) NOT NULL, previous_status VARCHAR(32) NOT NULL, new_status VARCHAR(32) NOT NULL, changed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_status_history_order_id ON order_status_history (order_id)');

        $this->addSql('CREATE TABLE IF NOT EXISTS order_payment_allocation (id SERIAL NOT NULL, payment_id INT NOT NULL, order_item_id INT DEFAULT NULL, amount NUMERIC(12, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_payment_allocation_payment_id ON order_payment_allocation (payment_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_payment_allocation_order_item_id ON order_payment_allocation (order_item_id)');

        $this->addSql('CREATE TABLE IF NOT EXISTS order_shipment_item (id SERIAL NOT NULL, shipment_id INT NOT NULL, order_item_id INT DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_shipment_item_shipment_id ON order_shipment_item (shipment_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_shipment_item_order_item_id ON order_shipment_item (order_item_id)');
    }

    public function down(Schema $schema): void
    {
    }
}
