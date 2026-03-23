<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144952 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 38 — Inventory Multi-Warehouse'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_warehouses (id SERIAL PRIMARY KEY, code VARCHAR(32) NOT NULL UNIQUE)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_warehouses
SQL
        );
    }
}
