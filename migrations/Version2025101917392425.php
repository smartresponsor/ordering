<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 117 — Proactive Autoscaling Policies';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_autoscale_policies (id SERIAL PRIMARY KEY, name VARCHAR(64) NOT NULL, target_qps INT NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_autoscale_policies
SQL
        );
    }
}
