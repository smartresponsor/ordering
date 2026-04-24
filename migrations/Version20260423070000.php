<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423070000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add doctrine-first order status history, payment allocation and shipment item storage';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS order_status_history (
            id SERIAL PRIMARY KEY,
            order_id VARCHAR(64) NOT NULL,
            from_status VARCHAR(32) NOT NULL,
            to_status VARCHAR(32) NOT NULL,
            changed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            reason VARCHAR(64) DEFAULT NULL
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_status_history_order_id ON order_status_history (order_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_status_history_changed_at ON order_status_history (changed_at)');

        $this->addSql('CREATE TABLE IF NOT EXISTS order_payment_allocation (
            id SERIAL PRIMARY KEY,
            payment_id INT NOT NULL,
            order_item_id INT DEFAULT NULL,
            amount NUMERIC(12,2) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_payment_allocation_payment_id ON order_payment_allocation (payment_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_payment_allocation_order_item_id ON order_payment_allocation (order_item_id)');

        $this->addSql('CREATE TABLE IF NOT EXISTS order_shipment_item (
            id SERIAL PRIMARY KEY,
            shipment_id INT NOT NULL,
            order_item_id INT DEFAULT NULL,
            quantity INT NOT NULL
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_shipment_item_shipment_id ON order_shipment_item (shipment_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_shipment_item_order_item_id ON order_shipment_item (order_item_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IF EXISTS idx_order_shipment_item_order_item_id');
        $this->addSql('DROP INDEX IF EXISTS idx_order_shipment_item_shipment_id');
        $this->addSql('DROP INDEX IF EXISTS idx_order_payment_allocation_order_item_id');
        $this->addSql('DROP INDEX IF EXISTS idx_order_payment_allocation_payment_id');
        $this->addSql('DROP INDEX IF EXISTS idx_order_status_history_changed_at');
        $this->addSql('DROP INDEX IF EXISTS idx_order_status_history_order_id');
        $this->addSql('DROP TABLE IF EXISTS order_shipment_item');
        $this->addSql('DROP TABLE IF EXISTS order_payment_allocation');
        $this->addSql('DROP TABLE IF EXISTS order_status_history');
    }
}
