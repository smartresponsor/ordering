<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355556 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 88 — DLP Gates'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_dlp_rules (id SERIAL PRIMARY KEY, rule VARCHAR(64) NOT NULL, severity VARCHAR(8) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_dlp_rules
SQL
        );
    }
}
