<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917224235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 77 — Reliability GameDays & Chaos';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_chaos_exercises (id SERIAL PRIMARY KEY, scenario VARCHAR(64) NOT NULL, outcome VARCHAR(32) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_chaos_exercises
SQL
        );
    }
}
