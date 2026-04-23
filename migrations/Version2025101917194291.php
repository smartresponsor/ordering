<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194291 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 64 — SLA/SLO Policies';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_sla (name VARCHAR(64) PRIMARY KEY, target_ms INT NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_sla
SQL
        );
    }
}
