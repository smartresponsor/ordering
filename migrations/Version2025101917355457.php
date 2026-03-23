<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355457 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 80 — Compensation Workflows'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_compensations (id SERIAL PRIMARY KEY, step VARCHAR(64) NOT NULL, action VARCHAR(64) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_compensations
SQL
        );
    }
}
