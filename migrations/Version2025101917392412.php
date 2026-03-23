<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392412 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 107 — Async Aggregation Pipelines'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_async_agg (id SERIAL PRIMARY KEY, job VARCHAR(64) NOT NULL, state VARCHAR(16) NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_async_agg
SQL
        );
    }
}
