<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 119 — Error Budget Policies';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_error_budget (service VARCHAR(64) PRIMARY KEY, budget NUMERIC(6,4) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_error_budget
SQL
        );
    }
}
