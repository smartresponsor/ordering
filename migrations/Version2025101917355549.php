<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 89 — Data Quality SLAs';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_data_sla (name VARCHAR(64) PRIMARY KEY, objective NUMERIC(6,4) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_data_sla
SQL
        );
    }
}
