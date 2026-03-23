<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355567 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 94 — SRE Dashboards'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_sre_dashboards (id SERIAL PRIMARY KEY, name VARCHAR(64) NOT NULL, url VARCHAR(256) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_sre_dashboards
SQL
        );
    }
}
