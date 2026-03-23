<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224222 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 69 — ERP Integration Contracts'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_erp_sync (id SERIAL PRIMARY KEY, payload JSON NOT NULL, status VARCHAR(16) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_erp_sync
SQL
        );
    }
}
