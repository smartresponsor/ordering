<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355597 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 92 — Canary & Auto-Rollback'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_canary (id SERIAL PRIMARY KEY, release VARCHAR(32) NOT NULL, status VARCHAR(16) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_canary
SQL
        );
    }
}
