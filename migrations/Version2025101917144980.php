<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144980 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 33 — LTS Baseline & Freeze'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_lts_baseline (k VARCHAR(64) PRIMARY KEY, v TEXT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_lts_baseline
SQL
        );
    }
}
