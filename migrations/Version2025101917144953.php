<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917144953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 35 — Promotions & Coupons';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_promotions (code VARCHAR(64) PRIMARY KEY, percent NUMERIC(5,2) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_promotions
SQL
        );
    }
}
