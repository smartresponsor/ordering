<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355585 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 95 — Capacity Planning';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_capacity_planning (id SERIAL PRIMARY KEY, region VARCHAR(16) NOT NULL, target_qps INT NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_capacity_planning
SQL
        );
    }
}
