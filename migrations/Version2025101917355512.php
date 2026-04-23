<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917355512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 83 — Failure Injection & Recovery';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_failure_injection (id SERIAL PRIMARY KEY, scenario VARCHAR(64) NOT NULL, enabled BOOLEAN NOT NULL DEFAULT false)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_failure_injection
SQL
        );
    }
}
