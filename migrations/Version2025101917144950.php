<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 42 — Outbox to Kafka Bridge (w/ RabbitMQ)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_outbox_kafka_bridge (id SERIAL PRIMARY KEY, state VARCHAR(16) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_outbox_kafka_bridge
SQL
        );
    }
}
