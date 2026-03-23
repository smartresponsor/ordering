<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144813 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 30 — Release v1.0.0 Contracts Lock'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_contract_lock (k VARCHAR(64) PRIMARY KEY, v TEXT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_contract_lock
SQL
        );
    }
}
