<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144975 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 41 — Idempotency Store: Redis/Postgres'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_idempotency_keys (k VARCHAR(128) PRIMARY KEY, created_at TIMESTAMP NOT NULL DEFAULT NOW())
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_idempotency_keys
SQL
        );
    }
}
