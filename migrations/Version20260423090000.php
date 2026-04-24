<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423090000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Doctrine-first storage consolidation for outbox, idempotency, events, transactions, shipment views, stock reservations, and webhook logs.';
    }

    public function up(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform()->getName();
        $jsonType = 'JSON';
        $datetime = 'postgresql' === $platform ? 'TIMESTAMP(0) WITHOUT TIME ZONE' : 'DATETIME';
        $bool = 'postgresql' === $platform ? 'BOOLEAN' : 'TINYINT(1)';

        $this->addSql("CREATE TABLE IF NOT EXISTS outbox_messages (message_id VARCHAR(36) NOT NULL, topic VARCHAR(191) NOT NULL, aggregate_id VARCHAR(191) NOT NULL, payload $jsonType NOT NULL, attempts INT NOT NULL DEFAULT 0, sent $bool NOT NULL DEFAULT 0, occurred_at $datetime NOT NULL, available_at $datetime DEFAULT NULL, dispatched_at $datetime DEFAULT NULL, PRIMARY KEY(message_id))");
        $this->addSql("CREATE TABLE IF NOT EXISTS idempotency_key (key_hash VARCHAR(128) NOT NULL, scope VARCHAR(191) NOT NULL, created_at $datetime NOT NULL, PRIMARY KEY(key_hash))");
        $this->addSql("CREATE TABLE IF NOT EXISTS order_event_record (event_id VARCHAR(36) NOT NULL, order_id VARCHAR(36) NOT NULL, event_name VARCHAR(191) NOT NULL, payload $jsonType NOT NULL, occurred_at $datetime NOT NULL, PRIMARY KEY(event_id))");
        $this->addSql("CREATE TABLE IF NOT EXISTS order_payment_transaction (id VARCHAR(36) NOT NULL, payment_id INT DEFAULT NULL, order_id VARCHAR(36) NOT NULL, amount NUMERIC(12, 2) NOT NULL, method VARCHAR(64) NOT NULL, status VARCHAR(32) NOT NULL, tx_id VARCHAR(128) DEFAULT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE TABLE IF NOT EXISTS order_refund_transaction (id VARCHAR(36) NOT NULL, order_id VARCHAR(36) NOT NULL, refund_id VARCHAR(128) NOT NULL, payment_ref VARCHAR(128) DEFAULT NULL, amount NUMERIC(12, 2) NOT NULL, currency VARCHAR(3) NOT NULL, reason TEXT DEFAULT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE TABLE IF NOT EXISTS order_shipment_view (order_id VARCHAR(36) NOT NULL, carrier VARCHAR(64) NOT NULL, tracking VARCHAR(128) NOT NULL, status VARCHAR(32) NOT NULL, delivered_at $datetime DEFAULT NULL, PRIMARY KEY(order_id))");
        $this->addSql("CREATE TABLE IF NOT EXISTS order_stock_reservation (order_id VARCHAR(36) NOT NULL, sku VARCHAR(128) NOT NULL, quantity INT NOT NULL, status VARCHAR(32) NOT NULL, PRIMARY KEY(order_id, sku))");
        $this->addSql("CREATE TABLE IF NOT EXISTS webhook_log (key VARCHAR(191) NOT NULL, event_type VARCHAR(191) NOT NULL, payload TEXT NOT NULL, PRIMARY KEY(key))");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS webhook_log');
        $this->addSql('DROP TABLE IF EXISTS order_stock_reservation');
        $this->addSql('DROP TABLE IF EXISTS order_shipment_view');
        $this->addSql('DROP TABLE IF EXISTS order_refund_transaction');
        $this->addSql('DROP TABLE IF EXISTS order_payment_transaction');
        $this->addSql('DROP TABLE IF EXISTS order_event_record');
        $this->addSql('DROP TABLE IF EXISTS idempotency_key');
        $this->addSql('DROP TABLE IF EXISTS outbox_messages');
    }
}
