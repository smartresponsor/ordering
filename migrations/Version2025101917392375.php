<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392375 extends AbstractMigration
{
    public function getDescription(): string { return 'Phase 100 — Fallback & Retries Policies'; }
    public function up(Schema $schema): void {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_retry_policy (id SERIAL PRIMARY KEY, name VARCHAR(64) NOT NULL, attempts INT NOT NULL)
SQL
        );
    }
    public function down(Schema $schema): void {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_retry_policy
SQL
        );
    }
}
