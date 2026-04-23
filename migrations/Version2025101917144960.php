<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144960 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 34 — Multi-currency & Advanced Taxation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
ALTER TABLE orders ADD COLUMN IF NOT EXISTS currency VARCHAR(3) NOT NULL DEFAULT 'USD'
SQL
        );
        $this->addSql(<<<'SQL'
ALTER TABLE orders ADD COLUMN IF NOT EXISTS fx_rate NUMERIC(12,6) NOT NULL DEFAULT 1.000000
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
ALTER TABLE orders DROP COLUMN IF EXISTS fx_rate
SQL
        );
        $this->addSql(<<<'SQL'
ALTER TABLE orders DROP COLUMN IF EXISTS currency
SQL
        );
    }
}
