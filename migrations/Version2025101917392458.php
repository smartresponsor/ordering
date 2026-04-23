<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917392458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 108 — Partial Materialization Read Models';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_partial_readmodels (id SERIAL PRIMARY KEY, view VARCHAR(64) NOT NULL, hydrated BOOLEAN NOT NULL DEFAULT false)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_partial_readmodels
SQL
        );
    }
}
