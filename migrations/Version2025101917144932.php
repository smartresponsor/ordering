<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 39 — Shipping Carriers & Tracking';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_shipments (id SERIAL PRIMARY KEY, tracking VARCHAR(64) NOT NULL UNIQUE)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_shipments
SQL
        );
    }
}
