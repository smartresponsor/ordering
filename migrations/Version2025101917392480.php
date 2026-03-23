<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392480 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 115 — DLP Pipelines v2'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_dlp_v2 (id SERIAL PRIMARY KEY, rule VARCHAR(64) NOT NULL, severity VARCHAR(8) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_dlp_v2
SQL
        );
    }
}
