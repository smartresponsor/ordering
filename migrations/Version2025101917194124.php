<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194124 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 57 — Catalog Sync Hooks'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_catalog_sync (id SERIAL PRIMARY KEY, job VARCHAR(64) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_catalog_sync
SQL
        );
    }
}
