<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423080000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Doctrine-first consolidation for outbox/idempotency and order technical storage';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE IF NOT EXISTS idempotency_keys (id SERIAL PRIMARY KEY, scope VARCHAR(128) NOT NULL, key_hash VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL)");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_idempotency_scope_hash ON idempotency_keys (scope, key_hash)");

        $this->addSql("CREATE TABLE IF NOT EXISTS outbox_messages (message_id VARCHAR(36) NOT NULL, topic VARCHAR(128) NOT NULL, payload JSON NOT NULL, attempts INT NOT NULL DEFAULT 0, sent BOOLEAN NOT NULL DEFAULT FALSE, available_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(message_id))");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_outbox_messages_available_pending ON outbox_messages (sent, available_at)");

        $this->addSql("CREATE TABLE IF NOT EXISTS order_event_record (id SERIAL PRIMARY KEY, event_id VARCHAR(64) NOT NULL, order_id VARCHAR(64) NOT NULL, event_name VARCHAR(128) NOT NULL, payload JSON NOT NULL, occurred_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL)");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_order_event_record_event_id ON order_event_record (event_id)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_order_event_record_order_id ON order_event_record (order_id)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_order_event_record_event_name ON order_event_record (event_name)");

        $this->addSql("CREATE TABLE IF NOT EXISTS order_payment_transaction (id VARCHAR(36) NOT NULL, order_id VARCHAR(64) NOT NULL, amount NUMERIC(12,2) NOT NULL, method VARCHAR(32) NOT NULL, status VARCHAR(32) NOT NULL, tx_id VARCHAR(128) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_order_payment_transaction_order_id ON order_payment_transaction (order_id)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_order_payment_transaction_status ON order_payment_transaction (status)");

        $this->addSql("CREATE TABLE IF NOT EXISTS order_refund_transaction (id VARCHAR(36) NOT NULL, order_id VARCHAR(64) NOT NULL, refund_id VARCHAR(64) NOT NULL, payment_ref VARCHAR(128) DEFAULT NULL, amount NUMERIC(12,2) NOT NULL, currency VARCHAR(8) NOT NULL, reason VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_order_refund_transaction_order_id ON order_refund_transaction (order_id)");

        $this->addSql("CREATE TABLE IF NOT EXISTS order_shipment_view (order_id VARCHAR(64) NOT NULL, carrier VARCHAR(64) NOT NULL, tracking VARCHAR(128) NOT NULL, status VARCHAR(32) NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(order_id))");

        $this->addSql("CREATE TABLE IF NOT EXISTS order_stock_reservation (id SERIAL PRIMARY KEY, order_id VARCHAR(64) NOT NULL, sku VARCHAR(128) NOT NULL, quantity INT NOT NULL, status VARCHAR(16) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_order_stock_reservation_order_sku ON order_stock_reservation (order_id, sku)");
        $this->addSql("CREATE INDEX IF NOT EXISTS idx_order_stock_reservation_sku_status ON order_stock_reservation (sku, status)");

        $this->addSql("CREATE TABLE IF NOT EXISTS order_webhook_log (id SERIAL PRIMARY KEY, idempotency_key VARCHAR(120) NOT NULL, event_type VARCHAR(64) NOT NULL, payload TEXT NOT NULL, received_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL)");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_order_webhook_log_key ON order_webhook_log (idempotency_key)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_webhook_log');
        $this->addSql('DROP TABLE IF EXISTS order_stock_reservation');
        $this->addSql('DROP TABLE IF EXISTS order_shipment_view');
        $this->addSql('DROP TABLE IF EXISTS order_refund_transaction');
        $this->addSql('DROP TABLE IF EXISTS order_payment_transaction');
        $this->addSql('DROP TABLE IF EXISTS order_event_record');
        $this->addSql('DROP TABLE IF EXISTS outbox_messages');
        $this->addSql('DROP TABLE IF EXISTS idempotency_keys');
    }
}
