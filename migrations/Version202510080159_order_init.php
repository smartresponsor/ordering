<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version202510080159_order_init extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init tables for Order Outbox & Idempotency';
    }

    public function up(Schema $schema): void
    {
        // outbox
        $this->addSql("CREATE TABLE IF NOT EXISTS order_outbox (
            id SERIAL PRIMARY KEY,
            aggregate_id VARCHAR(64) NOT NULL,
            event_type VARCHAR(128) NOT NULL,
            payload TEXT NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            dispatched BOOLEAN NOT NULL DEFAULT FALSE
        )");

        // idempotency
        $this->addSql("CREATE TABLE IF NOT EXISTS order_idempotency_key (
            id SERIAL PRIMARY KEY,
            key_value VARCHAR(120) NOT NULL UNIQUE,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS order_outbox');
        $this->addSql('DROP TABLE IF EXISTS order_idempotency_key');
    }
}
