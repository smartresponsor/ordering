<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355589 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 85 — Data Lake Export'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_datalake_exports (id SERIAL PRIMARY KEY, batch VARCHAR(64) NOT NULL, status VARCHAR(16) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_datalake_exports
SQL
        );
    }
}
