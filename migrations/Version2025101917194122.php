<?php
declare(strict_types=1);

namespace OrderComponent\Order\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version2025101917194122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Phase 60 — Geo Pricing';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
CREATE TABLE IF NOT EXISTS order_geo_pricing (id SERIAL PRIMARY KEY, region VARCHAR(16) NOT NULL)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
DROP TABLE IF EXISTS order_geo_pricing
SQL
        );
    }
}
