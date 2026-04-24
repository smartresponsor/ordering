<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423061000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Doctrine-first storage consolidation for order projections, reservations, transactions and webhook log';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS order_event_record (
            event_id VARCHAR(64) NOT NULL,
            order_id VARCHAR(64) NOT NULL,
            event_name VARCHAR(128) NOT NULL,
            payload JSON NOT NULL,
            occurred_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(event_id)
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_event_record_order_id ON order_event_record (order_id)');

        $this->addSql('CREATE TABLE IF NOT EXISTS order_payment_transaction (
            id VARCHAR(64) NOT NULL,
            order_id VARCHAR(64) NOT NULL,
            amount NUMERIC(12,2) NOT NULL,
            method VARCHAR(32) NOT NULL,
            status VARCHAR(32) NOT NULL,
            tx_id VARCHAR(128) DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_payment_transaction_order_id ON order_payment_transaction (order_id)');

        $this->addSql('CREATE TABLE IF NOT EXISTS order_shipment_view (
            order_id VARCHAR(64) NOT NULL,
            carrier VARCHAR(64) NOT NULL,
            tracking VARCHAR(128) NOT NULL,
            status VARCHAR(32) NOT NULL,
            delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(order_id)
        )');

        $this->addSql('CREATE TABLE IF NOT EXISTS order_stock_reservation (
            id SERIAL PRIMARY KEY,
            order_id VARCHAR(64) NOT NULL,
            sku VARCHAR(128) NOT NULL,
            quantity INT NOT NULL,
            status VARCHAR(32) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_stock_reservation_order_sku ON order_stock_reservation (order_id, sku)');
        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_stock_reservation_sku_status ON order_stock_reservation (sku, status)');

        $this->addSql('ALTER TABLE order_refund_transaction ADD COLUMN IF NOT EXISTS refund_id VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE order_refund_transaction ADD COLUMN IF NOT EXISTS payment_ref VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE order_refund_transaction ADD COLUMN IF NOT EXISTS amount NUMERIC(12,2) DEFAULT NULL');
        $this->addSql('ALTER TABLE order_refund_transaction ADD COLUMN IF NOT EXISTS reason TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_refund_transaction ADD COLUMN IF NOT EXISTS created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW()');
        $this->addSql("UPDATE order_refund_transaction SET amount = amount_minor / 100.0 WHERE amount IS NULL AND amount_minor IS NOT NULL");
        $this->addSql("UPDATE order_refund_transaction SET refund_id = COALESCE(refund_id, return_request_id, '') WHERE refund_id IS NULL");
        $this->addSql("UPDATE order_refund_transaction SET payment_ref = COALESCE(payment_ref, payment_id) WHERE payment_ref IS NULL");
        $this->addSql("ALTER TABLE order_refund_transaction ALTER COLUMN amount SET NOT NULL");
        $this->addSql("ALTER TABLE order_refund_transaction ALTER COLUMN refund_id SET NOT NULL");

        $this->addSql('CREATE INDEX IF NOT EXISTS idx_order_refund_transaction_order_id ON order_refund_transaction (order_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX IF EXISTS idx_order_refund_transaction_order_id');
        $this->addSql('DROP INDEX IF EXISTS idx_order_stock_reservation_sku_status');
        $this->addSql('DROP INDEX IF EXISTS idx_order_stock_reservation_order_sku');
        $this->addSql('DROP INDEX IF EXISTS idx_order_payment_transaction_order_id');
        $this->addSql('DROP INDEX IF EXISTS idx_order_event_record_order_id');
        $this->addSql('DROP TABLE IF EXISTS order_stock_reservation');
        $this->addSql('DROP TABLE IF EXISTS order_shipment_view');
        $this->addSql('DROP TABLE IF EXISTS order_payment_transaction');
        $this->addSql('DROP TABLE IF EXISTS order_event_record');
    }
}
