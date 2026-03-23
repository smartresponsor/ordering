<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355576 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 84 — Throughput & Backpressure'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_backpressure (k VARCHAR(64) PRIMARY KEY, threshold INT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_backpressure
SQL
        );
    }
}
