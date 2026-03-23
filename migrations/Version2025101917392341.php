<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392341 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 101 — Reconciliation & Settlement Reports'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_reconciliation (id SERIAL PRIMARY KEY, provider VARCHAR(32) NOT NULL, batch VARCHAR(64) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_reconciliation
SQL
        );
    }
}
