<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144961 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 44 — Data Retention, TTL & S3 Archival';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_audit_archive (id SERIAL PRIMARY KEY, aggregate_id VARCHAR(64) NOT NULL, ttl_days INT NOT NULL DEFAULT 365)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_audit_archive
SQL
        );
    }
}
