<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144881 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 31 — Post-Release Patch Window'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_patch_meta (k VARCHAR(64) PRIMARY KEY, v TEXT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_patch_meta
SQL
        );
    }
}
