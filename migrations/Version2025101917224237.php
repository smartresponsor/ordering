<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 76 — Cost Monitoring & FinOps';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_finops_costs (id SERIAL PRIMARY KEY, metric VARCHAR(64) NOT NULL, value NUMERIC(18,6) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_finops_costs
SQL
        );
    }
}
