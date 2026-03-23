<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194161 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 63 — Rate Limiting'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_rate_limits (key VARCHAR(128) PRIMARY KEY, quota INT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_rate_limits
SQL
        );
    }
}
