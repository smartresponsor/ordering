<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224242 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 72 — API v2 GA & Deprecations'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_api_v2_ga (k VARCHAR(64) PRIMARY KEY, v TEXT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_api_v2_ga
SQL
        );
    }
}
