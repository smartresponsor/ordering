<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917140516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 29 — API v1 Hardening';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS orders (id SERIAL PRIMARY KEY, total NUMERIC(12,2) NOT NULL DEFAULT 0)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS orders
SQL
        );
    }
}
