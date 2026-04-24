<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260423093000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Doctrine-first storage consolidation for outbox, idempotency, order item and technical order entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS message_id VARCHAR(36) DEFAULT '' NOT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS aggregate_id VARCHAR(64) DEFAULT '' NOT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS event_type VARCHAR(128) DEFAULT '' NOT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS attempts INT DEFAULT 0 NOT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS available_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS dispatched_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL");
        $this->addSql("ALTER TABLE outbox_messages ADD COLUMN IF NOT EXISTS failed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_outbox_message_id ON outbox_messages (message_id)");

        $this->addSql("ALTER TABLE idempotency_key ADD COLUMN IF NOT EXISTS scope VARCHAR(191) DEFAULT '' NOT NULL");
        $this->addSql("ALTER TABLE idempotency_key ADD COLUMN IF NOT EXISTS key_hash VARCHAR(64) DEFAULT '' NOT NULL");
        $this->addSql("UPDATE idempotency_key SET scope = COALESCE(NULLIF(scope, ''), key), key_hash = COALESCE(NULLIF(key_hash, ''), md5(key))");
        $this->addSql("CREATE UNIQUE INDEX IF NOT EXISTS uniq_idempotency_key_hash ON idempotency_key (key_hash)");

        $this->addSql('CREATE TABLE IF NOT EXISTS order_event_record (event_id VARCHAR(64) NOT NULL, order_id VARCHAR(64) NOT NULL, event_name VARCHAR(128) NOT NULL, payload JSON NOT NULL, occurred_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(event_id))');
        $this->addSql('CREATE TABLE IF NOT EXISTS order_shipment_view (order_id VARCHAR(64) NOT NULL, carrier VARCHAR(64) NOT NULL, tracking VARCHAR(128) NOT NULL, status VARCHAR(32) NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(order_id))');
        $this->addSql('CREATE TABLE IF NOT EXISTS order_stock_reservation (id SERIAL NOT NULL, order_id VARCHAR(64) NOT NULL, sku VARCHAR(64) NOT NULL, quantity INT NOT NULL, status VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE IF NOT EXISTS webhook_log (key VARCHAR(128) NOT NULL, event_type VARCHAR(128) NOT NULL, payload TEXT NOT NULL, PRIMARY KEY(key))');

        $this->addSql("ALTER TABLE order_item ADD COLUMN IF NOT EXISTS base_price NUMERIC(12,2) DEFAULT 0 NOT NULL");
        $this->addSql("ALTER TABLE order_item ADD COLUMN IF NOT EXISTS currency VARCHAR(3) DEFAULT 'USD' NOT NULL");
    }

    public function down(Schema $schema): void
    {
    }
}
